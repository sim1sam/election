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
      
      // Load all voter data on first visit (only if online and data not already loaded)
      if (navigator.onLine) {
        this.loadAllVoterData();
      }
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
    // Load all voter data if not already loaded
    this.loadAllVoterData();
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

      // Show loading notification
      this.showNotification('ডেটা লোড হচ্ছে', 'সমস্ত ভোটার তথ্য ডাউনলোড করা হচ্ছে...', 'info');

      // Fetch all voters from API
      const response = await fetch('/api/voters/all');
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();
      
      if (data.success && data.voters && data.voters.length > 0) {
        // Save all voters to IndexedDB
        await voterDB.saveVoters(data.voters);
        
        console.log(`[PWA] Loaded and saved ${data.voters.length} voters to IndexedDB`);
        this.showNotification('ডেটা লোড সম্পন্ন', `${data.voters.length} জন ভোটারের তথ্য সফলভাবে সংরক্ষণ করা হয়েছে`, 'success');
      } else {
        console.log('[PWA] No voters to load');
      }
    } catch (error) {
      console.error('[PWA] Error loading all voter data:', error);
      this.showNotification('ডেটা লোড ব্যর্থ', 'ভোটার তথ্য লোড করতে সমস্যা হয়েছে', 'warning');
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

