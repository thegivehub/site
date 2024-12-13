// Initialize the app with configuration
const givehub = {
  api: {
    baseUrl: 'https://api.thegivehub.com',
    version: 'v1',
    key: 'your-api-key'
  }
};

// Example 1: User Authentication
async function authenticateUser(email, password) {
  try {
    const response = await fetch(`${givehub.api.baseUrl}/auth/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        email,
        password
      })
    });

    const data = await response.json();
    if (data.success) {
      // Store tokens
      localStorage.setItem('accessToken', data.tokens.accessToken);
      localStorage.setItem('refreshToken', data.tokens.refreshToken);
      return data.user;
    }
    throw new Error(data.error);
  } catch (error) {
    console.error('Authentication failed:', error);
    throw error;
  }
}

// Example 2: Create a Campaign
async function createCampaign(campaignData) {
  try {
    const token = localStorage.getItem('accessToken');
    const response = await fetch(`${givehub.api.baseUrl}/campaigns`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify({
        title: campaignData.title,
        description: campaignData.description,
        target_amount: campaignData.targetAmount,
        milestones: campaignData.milestones,
        category: campaignData.category
      })
    });

    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Failed to create campaign:', error);
    throw error;
  }
}

// Example 3: Fetch Campaign Updates with Pagination
async function getCampaignUpdates(campaignId, page = 1, limit = 10) {
  try {
    const response = await fetch(
      `${givehub.api.baseUrl}/updates?campaignId=${campaignId}&page=${page}&limit=${limit}`
    );

    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Failed to fetch updates:', error);
    throw error;
  }
}

// Example 4: Process a Donation with Stellar
async function processDonation(campaignId, amount, currency) {
  try {
    const token = localStorage.getItem('accessToken');
    
    // First, get campaign Stellar address
    const campaignResponse = await fetch(`${givehub.api.baseUrl}/campaigns/${campaignId}`);
    const campaign = await campaignResponse.json();

    // Create donation record
    const donationResponse = await fetch(`${givehub.api.baseUrl}/donations`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify({
        campaignId,
        amount: {
          value: amount,
          currency: currency
        },
        type: 'one-time'
      })
    });

    const donation = await donationResponse.json();
    return donation;
  } catch (error) {
    console.error('Donation failed:', error);
    throw error;
  }
}

// Example 5: Track Impact Metrics
async function updateImpactMetrics(campaignId, metrics) {
  try {
    const token = localStorage.getItem('accessToken');
    const response = await fetch(`${givehub.api.baseUrl}/impactmetrics/${campaignId}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify({
        metrics: metrics.map(metric => ({
          name: metric.name,
          value: metric.value,
          unit: metric.unit,
          verificationMethod: metric.verificationMethod
        }))
      })
    });

    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Failed to update impact metrics:', error);
    throw error;
  }
}

// Example 6: Upload Media for Campaign Update
async function uploadUpdateMedia(campaignId, file) {
  try {
    const token = localStorage.getItem('accessToken');
    const formData = new FormData();
    formData.append('media', file);
    formData.append('campaignId', campaignId);

    const response = await fetch(`${givehub.api.baseUrl}/updates/media`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`
      },
      body: formData
    });

    const data = await response.json();
    return data.mediaUrl;
  } catch (error) {
    console.error('Media upload failed:', error);
    throw error;
  }
}

// Example 7: Handle Notifications
class NotificationHandler {
  constructor() {
    this.socket = null;
    this.callbacks = {};
  }

  connect(userId, token) {
    this.socket = new WebSocket(`${givehub.api.baseUrl.replace('http', 'ws')}/notifications`);
    
    this.socket.onopen = () => {
      this.socket.send(JSON.stringify({
        type: 'auth',
        token: token
      }));
    };

    this.socket.onmessage = (event) => {
      const notification = JSON.parse(event.data);
      if (this.callbacks[notification.type]) {
        this.callbacks[notification.type](notification);
      }
    };
  }

  on(eventType, callback) {
    this.callbacks[eventType] = callback;
  }
}

// Usage Examples:

// Authentication
const loginUser = async () => {
  try {
    const user = await authenticateUser('user@example.com', 'password123');
    console.log('Logged in as:', user.username);
  } catch (error) {
    console.error('Login failed:', error);
  }
};

// Create Campaign
const startCampaign = async () => {
  const campaignData = {
    title: 'Clean Water Project',
    description: 'Providing clean water access to remote communities',
    targetAmount: 50000,
    category: 'water',
    milestones: [
      {
        description: 'Phase 1: Site Survey',
        amount: 10000
      },
      {
        description: 'Phase 2: Well Construction',
        amount: 30000
      },
      {
        description: 'Phase 3: Distribution Network',
        amount: 10000
      }
    ]
  };

  try {
    const campaign = await createCampaign(campaignData);
    console.log('Campaign created:', campaign.id);
  } catch (error) {
    console.error('Failed to create campaign:', error);
  }
};

// Track Impact
const trackImpact = async (campaignId) => {
  const metrics = [
    {
      name: 'People with Water Access',
      value: 250,
      unit: 'individuals',
      verificationMethod: 'community_survey'
    },
    {
      name: 'Daily Water Output',
      value: 5000,
      unit: 'liters',
      verificationMethod: 'flow_meter'
    }
  ];

  try {
    await updateImpactMetrics(campaignId, metrics);
    console.log('Impact metrics updated successfully');
  } catch (error) {
    console.error('Failed to update impact metrics:', error);
  }
};

// Handle Real-time Notifications
const initializeNotifications = (userId, token) => {
  const notifications = new NotificationHandler();
  
  notifications.on('donation_received', (notification) => {
    console.log('New donation received:', notification.amount);
    // Update UI or trigger other actions
  });

  notifications.on('milestone_completed', (notification) => {
    console.log('Milestone completed:', notification.milestone.description);
    // Update campaign progress
  });

  notifications.connect(userId, token);
};
