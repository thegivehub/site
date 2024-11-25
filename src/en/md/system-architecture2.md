# Platform Architecture Documentation

## 1. System Overview
The Give Hub is a blockchain-enabled crowdfunding platform designed to facilitate secure, transparent, and verifiable donations to social causes in remote regions. Built on a distributed microservices architecture, the platform leverages the Stellar blockchain and Soroban smart contracts to ensure transparency, fund management, and real-time impact tracking.

## 2. Architecture Diagram
![](/img/system-architecture.svg)

## 3. Core Components

### 3.1 Frontend Layer
- **Framework**: Next.js 14
- **UI Library**: Material-UI
- **State Management**: React Query
- **Real-time Updates**: WebSocket
- **Authentication**: OAuth2 and JWT

### 3.2 Backend Services
Each service operates as a Docker container, managed by Kubernetes:

1. **Campaign Service**
   - Node.js/Express backend
   - MongoDB for campaign data
   - Campaign creation/management
   - File storage (AWS S3)
   - Analytics pipeline

2. **User Service**
   - Authentication/Authorization
   - Profile management
   - KYC verification
   - Role-based access control

3. **Impact Service**
   - Metric tracking
   - SDG alignment
   - Real-time analytics
   - Progress reporting

4. **Payment Service**
   - Stellar integration
   - Transaction processing
   - Multi-currency support
   - Smart contract management

5. **Updates Service**
   - Project updates
   - Media management
   - Verification system
   - Notification handling

## 4. Data Architecture

### 4.1 MongoDB Schema
```javascript
// Campaigns Collection
{
  _id: ObjectId,
  title: String,
  description: String,
  target_amount: Number,
  current_amount: Number,
  creator_id: ObjectId,
  stellar_account: String,
  milestones: [{
    description: String,
    amount: Number,
    status: String,
    verification_docs: [String]
  }],
  status: String,
  created_at: Date
}

// Users Collection
{
  _id: ObjectId,
  email: String,
  role: String,
  stellar_wallet: String,
  kyc_status: String,
  created_at: Date
}

// Transactions Collection
{
  _id: ObjectId,
  campaign_id: ObjectId,
  user_id: ObjectId,
  stellar_tx_id: String,
  amount: Number,
  type: String,
  status: String
}

// Milestones Collection
{
  _id: ObjectId,
  campaign_id: ObjectId,
  description: String,
  amount: Number,
  status: String,
  verification_docs: [String],
  completed_at: Date
}
```

### 4.2 Blockchain Integration
```javascript
// Soroban Smart Contract State
{
  campaign_id: String,
  total_funds: Number,
  milestones: [{
    amount: Number,
    released: Boolean,
    verification_hash: String
  }],
  donors: [{
    address: String,
    amount: Number
  }]
}
```

## 5. Process Flows

### 5.1 Campaign Creation Flow
![](/charts/system-architecture-1.svg)

### 5.2 Donation Flow
![](/charts/system-architecture-2.svg)

## 6. Security Architecture

### 6.1 Authentication & Authorization
- JWT-based authentication
- Role-based access control
- Multi-factor authentication
- Wallet signature verification

### 6.2 Data Security
- End-to-end encryption
- HTTPS/TLS
- Regular security audits
- Smart contract auditing

## 7. Deployment Architecture
- AWS cloud hosting
- Docker containerization
- Kubernetes orchestration
- MongoDB Atlas
- ELK Stack for logging
- Prometheus/Grafana monitoring

## 8. Scalability & Performance
- Horizontal scaling via Kubernetes
- MongoDB sharding
- Redis caching
- Load balancing
- CDN implementation

## 9. Disaster Recovery
- Regular automated backups
- Multi-region deployment
- Failover procedures
- Data consistency checks
- Smart contract state recovery
