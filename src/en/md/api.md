# The Give Hub API Documentation

## Overview
The Give Hub API provides RESTful endpoints for managing campaigns, donations, users, and impact metrics on the crowdfunding platform. All endpoints are accessed via HTTPS and return JSON responses.

## Base URL
```
https://api.thegivehub.com
```

## Authentication
All authenticated endpoints require a valid JWT token in the Authorization header:
```
Authorization: Bearer <token>
```

## Endpoints

### Authentication

#### Login

- **POST** `/api/auth/login`
- Authenticates a user and returns access tokens
- **Body:**
  ```json
  {
    "username": "string", // Email or username
    "password": "string"
  }
  ```
- **Response:**
  ```json
  {
    "success": true,
    "user": {
      "id": "string",
      "email": "string",
      "username": "string",
      "profile": {...}
    },
    "tokens": {
      "accessToken": "string",
      "refreshToken": "string",
      "expires": "number"
    }
  }
  ```

#### Register

- **POST** `/api/auth/register`
- Creates a new user account
- **Body:**
  ```json
  {
    "email": "string",
    "username": "string",
    "password": "string",
    "firstName": "string",
    "lastName": "string",
    "lang": "string",
    "profile": {
      // Additional profile fields
    }
  }
  ```
- **Response:**
  ```json
  {
    "success": true,
    "message": "string",
    "userId": "string"
  }
  ```

#### Verify Email

- **POST** `/api/auth/request-verification`
- Verifies user's email with verification code
- **Body:**
  ```json
  {
    "email": "string",
    "code": "string"
  }
  ```

### Campaigns

#### Create Campaign

- **POST** `/api/campaigns`
- Creates a new fundraising campaign
- **Required Auth:** Yes
- **Body:**
  ```json
  {
    "title": "string",
    "description": "string",
    "target_amount": "number",
    "creator_id": "string",
    "stellar_account": "string",
    "milestones": [{
      "description": "string",
      "amount": "number",
      "status": "string",
      "verification_docs": ["string"]
    }],
    "status": "string"
  }
  ```

#### Get Campaigns

- **GET** `/api/campaigns`
- Returns list of campaigns
- **Query Parameters:**
  - `status`: Filter by status
  - `category`: Filter by category
  - `page`: Page number
  - `limit`: Results per page

#### Get Campaign Details

- **GET** `/api/campaigns/:id`
- Returns detailed campaign information
- **Response:**
  ```json
  {
    "_id": "string",
    "title": "string",
    "description": "string",
    "target_amount": "number",
    "current_amount": "number",
    "creator_id": "string",
    "stellar_account": "string",
    "milestones": [...],
    "status": "string",
    "created_at": "date"
  }
  ```

### Impact Metrics

#### Create Impact Metric

- **POST** `/api/impactmetrics`
- Records impact metrics for a campaign
- **Required Auth:** Yes
- **Body:**
  ```json
  {
    "campaignId": "string",
    "category": "string",
    "metrics": [{
      "name": "string",
      "baseline": "number",
      "current": "number",
      "target": "number",
      "unit": "string",
      "verificationMethod": "string"
    }],
    "beneficiaries": {
      "direct": "number",
      "indirect": "number"
    }
  }
  ```

#### Get Impact Metrics
- **GET** `/api/impactmetrics/:campaignId`
- Returns impact metrics for a campaign
- **Query Parameters:**
  - `from`: Start date
  - `to`: End date

### Updates

#### Create Update

- **POST** `/api/updates`
- Posts an update to a campaign
- **Required Auth:** Yes
- **Body:**
  ```json
  {
    "campaignId": "string",
    "type": "string",
    "title": "string",
    "content": "string",
    "media": [{
      "type": "string",
      "url": "string",
      "caption": "string"
    }]
  }
  ```

#### Get Updates

- **GET** `/api/updates`
- Returns campaign updates
- **Query Parameters:**
  - `campaignId`: Filter by campaign
  - `type`: Update type
  - `page`: Page number
  - `limit`: Results per page

### Donations

#### Create Donation

- **POST** `/api/donations`
- Records a new donation
- **Required Auth:** Yes
- **Body:**
  ```json
  {
    "userId": "string",
    "campaignId": "string",
    "amount": {
      "value": "number",
      "currency": "string"
    },
    "transaction": {
      "txHash": "string",
      "stellarAddress": "string"
    }
  }
  ```

#### Get Donations

- **GET** `/api/donations`
- Returns donation records
- **Query Parameters:**
  - `campaignId`: Filter by campaign
  - `userId`: Filter by user
  - `status`: Filter by status

### Notifications

#### Get User Notifications

- **GET** `/api/notifications`
- Returns user notifications
- **Required Auth:** Yes
- **Query Parameters:**
  - `status`: Filter by status
  - `type`: Filter by type
  - `page`: Page number

#### Update Notification Status

- **PUT** `/api/notifications/:id`
- Updates notification status
- **Required Auth:** Yes
- **Body:**
  ```json
  {
    "status": "string" // read, archived, deleted
  }
  ```

## Error Responses
All endpoints return error responses in the following format:
```json
{
  "success": false,
  "error": "Error message"
}
```

Common HTTP status codes:

- `200`: Success
- `400`: Bad Request
- `401`: Unauthorized
- `403`: Forbidden
- `404`: Not Found
- `451`: Government Blocked
- `500`: Internal Server Error

## Rate Limiting
API requests are limited to:

- 100 requests per minute for authenticated users
- 20 requests per minute for unauthenticated users

## Webhook Events
The API can send webhook notifications for the following events:

- Campaign created/updated
- Donation received
- Milestone completed
- Impact metric updated
- Verification required

## Development Environment
A sandbox environment is available for testing:
```
https://sandbox-api.thegivehub.com
```

## SDK Support
Official SDK libraries are available for:

- [JavaScript/Node.js](/sdk/js/)
- [PHP](/sdk/php/)
- [Python](/sdk/python/)

## Additional Resources

- [API Changelog](/api/changelog)
- [Sample Code](/api/examples)
- [Webhook Integration Guide](/api/webhooks)
