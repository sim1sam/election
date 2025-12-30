// IndexedDB Helper for Voter Data Storage
class VoterDB {
  constructor() {
    this.dbName = 'VoterInfoDB';
    this.dbVersion = 1;
    this.storeName = 'voters';
    this.db = null;
  }

  // Initialize database
  async init() {
    return new Promise((resolve, reject) => {
      const request = indexedDB.open(this.dbName, this.dbVersion);

      request.onerror = () => {
        console.error('[IndexedDB] Database open error:', request.error);
        reject(request.error);
      };

      request.onsuccess = () => {
        this.db = request.result;
        console.log('[IndexedDB] Database opened successfully');
        resolve(this.db);
      };

      request.onupgradeneeded = (event) => {
        const db = event.target.result;
        
        // Create object store if it doesn't exist
        if (!db.objectStoreNames.contains(this.storeName)) {
          const objectStore = db.createObjectStore(this.storeName, {
            keyPath: 'id',
            autoIncrement: false
          });
          
          // Create indexes for searching
          objectStore.createIndex('voter_number', 'voter_number', { unique: false });
          objectStore.createIndex('ward_number', 'ward_number', { unique: false });
          objectStore.createIndex('date_of_birth', 'date_of_birth', { unique: false });
          objectStore.createIndex('voter_serial_number', 'voter_serial_number', { unique: false });
          
          console.log('[IndexedDB] Object store created');
        }
      };
    });
  }

  // Save voter data
  async saveVoter(voter) {
    if (!this.db) {
      await this.init();
    }

    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction([this.storeName], 'readwrite');
      const store = transaction.objectStore(this.storeName);
      const request = store.put(voter);

      request.onsuccess = () => {
        resolve(request.result);
      };

      request.onerror = () => {
        console.error('[IndexedDB] Save error:', request.error);
        reject(request.error);
      };
    });
  }

  // Save multiple voters efficiently (batch processing)
  async saveVoters(voters) {
    if (!this.db) {
      await this.init();
    }

    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction([this.storeName], 'readwrite');
      const store = transaction.objectStore(this.storeName);
      let completed = 0;
      let errors = [];

      // Process in batches to avoid overwhelming the transaction
      const batchSize = 100;
      let currentBatch = 0;

      const processBatch = () => {
        const start = currentBatch * batchSize;
        const end = Math.min(start + batchSize, voters.length);
        
        if (start >= voters.length) {
          if (errors.length > 0) {
            reject(errors);
          } else {
            console.log(`[IndexedDB] Saved ${completed} voters`);
            resolve(completed);
          }
          return;
        }

        const batch = voters.slice(start, end);
        batch.forEach((voter) => {
          const request = store.put(voter);
          request.onsuccess = () => {
            completed++;
            if (completed === voters.length) {
              console.log(`[IndexedDB] Saved ${completed} voters`);
              resolve(completed);
            }
          };
          request.onerror = () => {
            errors.push(request.error);
            completed++;
            if (completed === voters.length) {
              if (errors.length > 0) {
                reject(errors);
              } else {
                resolve(completed);
              }
            }
          };
        });

        currentBatch++;
        setTimeout(processBatch, 10);
      };

      processBatch();
    });
  }

  // Get voter by ID
  async getVoter(id) {
    if (!this.db) {
      await this.init();
    }

    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction([this.storeName], 'readonly');
      const store = transaction.objectStore(this.storeName);
      const request = store.get(id);

      request.onsuccess = () => {
        resolve(request.result);
      };

      request.onerror = () => {
        reject(request.error);
      };
    });
  }

  // Search voters by ward number and date of birth
  async searchVoters(wardNumber, dateOfBirth) {
    if (!this.db) {
      await this.init();
    }

    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction([this.storeName], 'readonly');
      const store = transaction.objectStore(this.storeName);
      const results = [];

      // Use index for ward_number
      const index = store.index('ward_number');
      const request = index.openCursor(IDBKeyRange.only(wardNumber));

      request.onsuccess = (event) => {
        const cursor = event.target.result;
        if (cursor) {
          const voter = cursor.value;
          // Filter by date of birth
          if (voter.date_of_birth === dateOfBirth) {
            results.push(voter);
          }
          cursor.continue();
        } else {
          console.log(`[IndexedDB] Found ${results.length} voters`);
          resolve(results);
        }
      };

      request.onerror = () => {
        reject(request.error);
      };
    });
  }

  // Get all voters
  async getAllVoters() {
    if (!this.db) {
      await this.init();
    }

    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction([this.storeName], 'readonly');
      const store = transaction.objectStore(this.storeName);
      const request = store.getAll();

      request.onsuccess = () => {
        resolve(request.result);
      };

      request.onerror = () => {
        reject(request.error);
      };
    });
  }

  // Clear all voters
  async clearAll() {
    if (!this.db) {
      await this.init();
    }

    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction([this.storeName], 'readwrite');
      const store = transaction.objectStore(this.storeName);
      const request = store.clear();

      request.onsuccess = () => {
        console.log('[IndexedDB] All voters cleared');
        resolve();
      };

      request.onerror = () => {
        reject(request.error);
      };
    });
  }

  // Get count of stored voters
  async getCount() {
    if (!this.db) {
      await this.init();
    }

    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction([this.storeName], 'readonly');
      const store = transaction.objectStore(this.storeName);
      const request = store.count();

      request.onsuccess = () => {
        resolve(request.result);
      };

      request.onerror = () => {
        reject(request.error);
      };
    });
  }

  // Check if data is already loaded
  async isDataLoaded() {
    const count = await this.getCount();
    return count > 0;
  }
}

// Initialize global instance
const voterDB = new VoterDB();

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
  module.exports = { VoterDB, voterDB };
}

