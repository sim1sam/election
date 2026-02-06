// PWA Service Worker Registration and Offline Handling
class PWAHelper {
  constructor() {
    this.serviceWorkerRegistration = null;
    this.isOnline = navigator.onLine;
    this.init();
  }

  async init() {
    // Register service worker
    if ('serviceWorker' in navigator) {
      await this.registerServiceWorker();
    }

    // Setup online/offline listeners
    this.setupOnlineOfflineListeners();

    // Initialize IndexedDB
    if (typeof voterDB !== 'undefined') {
      await voterDB.init();
      
      // Don't load voter data automatically on homepage - only load when needed (lazy loading)
      // Data will be loaded when user visits the search page or when explicitly requested
      // This prevents timeout issues on initial page load
    }
  }

  async registerServiceWorker() {
    try {
      const registration = await navigator.serviceWorker.register('/sw.js', {
        scope: '/'
      });

      this.serviceWorkerRegistration = registration;
      console.log('[PWA] Service Worker registered:', registration.scope);

      // Check for updates
      registration.addEventListener('updatefound', () => {
        const newWorker = registration.installing;
        console.log('[PWA] New service worker found');

        newWorker.addEventListener('statechange', () => {
          if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
            this.showUpdateNotification();
          }
        });
      });

      navigator.serviceWorker.addEventListener('controllerchange', () => {
        console.log('[PWA] Service worker controller changed');
        window.location.reload();
      });

    } catch (error) {
      console.error('[PWA] Service Worker registration failed:', error);
    }
  }

  setupOnlineOfflineListeners() {
    window.addEventListener('online', () => {
      this.isOnline = true;
      console.log('[PWA] Online');
      this.onOnline();
    });

    window.addEventListener('offline', () => {
      this.isOnline = false;
      console.log('[PWA] Offline');
      this.onOffline();
    });
  }

  onOnline() {
    this.showNotification('অনলাইন', 'ইন্টারনেট সংযোগ পুনরুদ্ধার হয়েছে', 'success');
    // Don't auto-load voter data when coming online - only load when user visits search page
    // This prevents timeout issues. Data will be loaded lazily when needed.
  }

  onOffline() {
    this.showNotification('অফলাইন', 'ইন্টারনেট সংযোগ নেই। ক্যাশ থেকে ডেটা দেখানো হবে', 'warning');
    this.loadFromCache();
  }

  async loadFromCache() {
    if (typeof voterDB !== 'undefined') {
      try {
        const count = await voterDB.getCount();
        if (count > 0) {
          console.log(`[PWA] Loaded ${count} voters from cache`);
          this.showNotification('ক্যাশ থেকে লোড', `${count} জন ভোটারের তথ্য ক্যাশ থেকে লোড করা হয়েছে`, 'info');
        }
      } catch (error) {
        console.error('[PWA] Error loading from cache:', error);
      }
    }
  }

  // Load all voter data from API on first visit
  async loadAllVoterData() {
    if (typeof voterDB === 'undefined') {
      return;
    }

    try {
      // Check if data is already loaded
      const isLoaded = await voterDB.isDataLoaded();
      if (isLoaded) {
        console.log('[PWA] Voter data already loaded in IndexedDB');
        return;
      }

      // Check if we're online
      if (!navigator.onLine) {
        console.log('[PWA] Offline - cannot load voter data');
        return;
      }

      // Fetch all voters from API in chunks (pagination) - no loading notification
      let page = 1;
      let allVoters = [];
      let totalLoaded = 0;
      let totalCount = 0;
      
      while (true) {
        try {
          const response = await fetch(`/api/voters/all?page=${page}&per_page=100`, {
            signal: AbortSignal.timeout(20000) // 20 second timeout per request (reduced from 30s)
          });
          
          if (!response.ok) {
            if (response.status === 500 || response.status === 503) {
              throw new Error('Server error. Please try again later.');
            }
            throw new Error(`HTTP error! status: ${response.status}`);
          }

          const data = await response.json();
          
          if (!data.success || !data.voters || data.voters.length === 0) {
            break;
          }
          
          // Store total count from first page
          if (page === 1) {
            totalCount = data.total_count || data.voters.length;
          }
          
          // Add voters to array
          allVoters = allVoters.concat(data.voters);
          totalLoaded += data.voters.length;
          
          // Check if there are more pages
          if (!data.has_more || data.voters.length < data.per_page) {
            break;
          }
          
          page++;
          
          // Delay to prevent overwhelming the server
          await new Promise(resolve => setTimeout(resolve, 200));
        } catch (error) {
          if (error.name === 'TimeoutError') {
            console.error('[PWA] Request timeout, retrying...');
            // Retry once
            await new Promise(resolve => setTimeout(resolve, 1000));
            continue;
          }
          throw error;
        }
      }
      
      if (allVoters.length > 0) {
        // Save all voters to IndexedDB in batches
        const batchSize = 500;
        for (let i = 0; i < allVoters.length; i += batchSize) {
          const batch = allVoters.slice(i, i + batchSize);
          await voterDB.saveVoters(batch);
          console.log(`[PWA] Saved batch ${Math.floor(i / batchSize) + 1}, ${batch.length} voters`);
        }
        
        console.log(`[PWA] Loaded and saved ${allVoters.length} voters to IndexedDB`);
        this.showNotification('ডেটা লোড সম্পন্ন', `${allVoters.length} জন ভোটারের তথ্য সফলভাবে সংরক্ষণ করা হয়েছে`, 'success');
      } else {
        console.log('[PWA] No voters to load');
      }
    } catch (error) {
      console.error('[PWA] Error loading all voter data:', error);
    }
  }

  showUpdateNotification() {
    if (confirm('নতুন সংস্করণ পাওয়া গেছে। আপডেট করতে চান?')) {
      if (this.serviceWorkerRegistration && this.serviceWorkerRegistration.waiting) {
        this.serviceWorkerRegistration.waiting.postMessage({ type: 'SKIP_WAITING' });
      }
    }
  }

  showNotification(title, message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `pwa-notification pwa-notification-${type}`;
    notification.innerHTML = `
      <div class="pwa-notification-content">
        <strong>${title}</strong>
        <p>${message}</p>
      </div>
      <button class="pwa-notification-close" onclick="this.parentElement.remove()">×</button>
    `;

    if (!document.getElementById('pwa-notification-styles')) {
      const style = document.createElement('style');
      style.id = 'pwa-notification-styles';
      style.textContent = `
        .pwa-notification {
          position: fixed;
          top: 20px;
          right: 20px;
          background: rgba(255, 255, 255, 0.95);
          color: #333;
          padding: 15px 20px;
          border-radius: 8px;
          box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
          z-index: 10000;
          max-width: 300px;
          animation: slideIn 0.3s ease;
        }
        .pwa-notification-success {
          border-left: 4px solid #28a745;
        }
        .pwa-notification-warning {
          border-left: 4px solid #ffc107;
        }
        .pwa-notification-info {
          border-left: 4px solid #17a2b8;
        }
        .pwa-notification-content {
          margin-right: 25px;
        }
        .pwa-notification-close {
          position: absolute;
          top: 5px;
          right: 10px;
          background: none;
          border: none;
          font-size: 24px;
          cursor: pointer;
          color: #666;
        }
        @keyframes slideIn {
          from {
            transform: translateX(100%);
            opacity: 0;
          }
          to {
            transform: translateX(0);
            opacity: 1;
          }
        }
      `;
      document.head.appendChild(style);
    }

    document.body.appendChild(notification);

    setTimeout(() => {
      if (notification.parentElement) {
        notification.remove();
      }
    }, 5000);
  }

  // Clear all cache
  async clearAllCache() {
    try {
      // Clear IndexedDB
      if (typeof voterDB !== 'undefined') {
        await voterDB.clearAll();
        console.log('[PWA] IndexedDB cleared');
      }
      
      // Clear Service Worker Cache
      if ('caches' in window) {
        const cacheNames = await caches.keys();
        await Promise.all(
          cacheNames.map(cacheName => caches.delete(cacheName))
        );
        console.log('[PWA] Service Worker caches cleared');
      }
      
      // Unregister Service Worker
      if ('serviceWorker' in navigator) {
        const registrations = await navigator.serviceWorker.getRegistrations();
        await Promise.all(
          registrations.map(registration => registration.unregister())
        );
        console.log('[PWA] Service Workers unregistered');
      }
      
      // Clear localStorage
      localStorage.clear();
      console.log('[PWA] localStorage cleared');
      
      // Clear sessionStorage
      sessionStorage.clear();
      console.log('[PWA] sessionStorage cleared');
      
      this.showNotification('ক্যাশ সাফ করা হয়েছে', 'সমস্ত ক্যাশ ডেটা সফলভাবে সাফ করা হয়েছে', 'success');
      
      // Reload page after 2 seconds
      setTimeout(() => {
        window.location.reload();
      }, 2000);
      
      return true;
    } catch (error) {
      console.error('[PWA] Error clearing cache:', error);
      this.showNotification('ক্যাশ সাফ ব্যর্থ', 'ক্যাশ সাফ করতে সমস্যা হয়েছে', 'warning');
      return false;
    }
  }
}

// Initialize PWA Helper
const pwaHelper = new PWAHelper();

// Search from IndexedDB when offline
async function searchFromIndexedDB(wardNumber, dateOfBirth) {
  if (typeof voterDB !== 'undefined') {
    try {
      // Convert date from dd/mm/yyyy to Y-m-d format
      const dateParts = dateOfBirth.split('/');
      const searchDate = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;
      
      const results = await voterDB.searchVoters(wardNumber, searchDate);
      return results;
    } catch (error) {
      console.error('[PWA] Error searching IndexedDB:', error);
      return [];
    }
  }
  return [];
}

// Global function to clear all cache (can be called from browser console)
async function clearAllCache() {
  if (typeof pwaHelper !== 'undefined') {
    return await pwaHelper.clearAllCache();
  } else {
    console.error('[PWA] PWA Helper not initialized');
    return false;
  }
}

// Make clearAllCache available globally
window.clearAllCache = clearAllCache;

// Make loadAllVoterData available globally for manual triggering
window.loadVoterData = async function() {
  if (typeof pwaHelper !== 'undefined') {
    return await pwaHelper.loadAllVoterData();
  } else {
    console.error('[PWA] PWA Helper not initialized');
    return false;
  }
};

