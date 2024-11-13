const mongoose = require('mongoose');

const campaignSchema = new mongoose.Schema({
  title: {
    type: String,
    required: true,
    trim: true
  },
  description: {
    type: String,
    required: true
  },
  location: {
    country: {
      type: String,
      required: true
    },
    region: {
      type: String,
      required: true
    },
    coordinates: {
      latitude: Number,
      longitude: Number
    }
  },
  funding: {
    goalAmount: {
      type: Number,
      required: true
    },
    raisedAmount: {
      type: Number,
      default: 0
    },
    currency: {
      type: String,
      default: 'XLM'
    },
    donorCount: {
      type: Number,
      default: 0
    },
    transactions: [{
      txHash: String,
      amount: Number,
      timestamp: Date,
      donorId: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User'
      }
    }]
  }
});


const Campaign = mongoose.model('Campaign', campaignSchema);
module.exports = Campaign;


require('mongoose');

const impactMetricsSchema = new mongoose.Schema({
  campaignId: ObjectId,
  category: String,  // education, water, health, etc.
  baselineDate: Date,
  metrics: [{
    name: String,
    baseline: Number,
    current: Number,
    target: Number,
    unit: String,
    verificationMethod: String,
    frequency: String,  // daily, weekly, monthly
    history: [{
      value: Number,
      date: Date,
      verifiedBy: ObjectId
    }]
  }],
  sdgAlignment: [{
    goalNumber: Number,
    targets: [String],
    contribution: String
  }],
  beneficiaries: {
    direct: Number,
    indirect: Number,
    demographics: [{
      category: String,
      count: Number
    }]
  },
  qualitativeData: [{
    type: String,  // testimonial, case study, observation
    date: Date,
    content: String,
    source: String,
    verification: {
      verifiedBy: ObjectId,
      date: Date,
      method: String
    }
  }]
});

const ImpactMetrics = mongoose.model('ImpactMetrics', impactMetricsSchema);
module.exports = ImpactMetrics;




require('mongoose');

const notificationSchema = new mongoose.Schema({
  type: String,  // campaign update, donation, milestone, alert
  priority: String,  // high, medium, low
  status: String,  // unread, read, archived
  timestamp: Date,
  recipient: {
    userId: ObjectId,
    deliveryMethods: [String]  // email, push, in-app
  },
  content: {
    title: String,
    body: String,
    action: {
      type: String,
      link: String
    }
  },
  referenceData: {
    entityType: String,
    entityId: ObjectId,
    context: Mixed
  },
  delivery: {
    attempts: [{
      method: String,
      timestamp: Date,
      status: String
    }],
    lastDelivered: Date
  }
});

const Notification = mongoose.model('Notification', notificationSchema);
module.exports = Notification;

const mongoose = require('mongoose');

const organizationSchema = new mongoose.Schema( {
  name: String,
  type: String,  // NGO, Government, Community, Corporate
  status: String,  // active, pending, suspended
  legalInfo: {
    registrationNumber: String,
    taxId: String,
    country: String,
    registrationDate: Date,
    documents: [{
      type: String,
      url: String,
      expiryDate: Date,
      verified: Boolean
    }]
  },
  contacts: [{
    userId: ObjectId,
    role: String,
    isPrimary: Boolean,
    department: String
  }],
  location: {
    address: String,
    city: String,
    region: String,
    country: String,
    coordinates: {
      latitude: Number,
      longitude: Number
    }
  },
  finance: {
    stellarAccount: String,
    bankDetails: {
      bankName: String,
      accountNumber: String,
      swiftCode: String
    },
    reportingCurrency: String
  },
  verification: {
    status: String,
    verifiedBy: ObjectId,
    verificationDate: Date,
    documents: [{
      type: String,
      url: String,
      status: String,
      notes: String
    }]
  },
  projects: [{
    campaignId: ObjectId,
    role: String,  // lead, partner, supporter
    status: String
  }],
  ratings: {
    overallScore: Number,
    totalReviews: Number,
    categories: [{
      name: String,  // transparency, impact, communication
      score: Number
    }]
  }
});

const Organization = mongoose.model('Organization', organizationSchema);
module.exports = Organization;



const mongoose = require('mongoose');

const updateSchema = new mongoose.Schema({
  campaignId: ObjectId,
  type: String,  // milestone, general, emergency, success
  title: String,
  content: String,
  date: Date,
  author: {
    userId: ObjectId,
    role: String  // projectManager, localPartner, administrator
  },
  media: [{
    type: String,  // image, video, document
    url: String,
    caption: String,
    timestamp: Date
  }],
  metrics: [{
    name: String,
    value: Number,
    previousValue: Number,
    unit: String
  }],
  verification: {
    status: String,
    verifiedBy: ObjectId,
    verificationDate: Date,
    notes: String
  }
});

const Update = mongoose.model('Update', updateSchema);
module.exports = Update;



const mongoose = require('mongoose');

const userSchema = new mongoose.Schema({
  type: String,  // donor, projectManager, localPartner, administrator
  status: String,  // active, suspended, pending
  personalInfo: {
    firstName: String,
    lastName: String,
    email: String,
    phone: String,
    avatar: String,
    timezone: String,
    language: String
  },
  authentication: {
    passwordHash: String,
    twoFactorEnabled: Boolean,
    lastLogin: Date,
    loginHistory: [{
      date: Date,
      ip: String,
      device: String
    }]
  },
  wallet: {
    stellarPublicKey: String,
    preferredCurrency: String,
    transactions: [{
      type: String,  // donation, withdrawal, refund
      amount: Number,
      currency: String,
      timestamp: Date,
      txHash: String,
      status: String
    }]
  },
  roles: [{
    type: String,
    organizationId: ObjectId,
    permissions: [String]
  }],
  preferences: {
    notifications: {
      email: Boolean,
      push: Boolean,
      updateFrequency: String,
      subscribedTopics: [String]
    },
    privacy: {
      isAnonymous: Boolean,
      shareActivity: Boolean
    },
    interests: [String]  // categories of projects
  },
  activity: {
    donations: [{
      campaignId: ObjectId,
      amount: Number,
      date: Date,
      txHash: String,
      status: String
    }],
    comments: [{
      campaignId: ObjectId,
      content: String,
      date: Date,
      status: String  // active, deleted, flagged
    }],
    volunteering: [{
      campaignId: ObjectId,
      role: String,
      hours: Number,
      startDate: Date,
      endDate: Date
    }]
  }
});

const User = mongoose.model('User', userSchema);
module.exports = User;


