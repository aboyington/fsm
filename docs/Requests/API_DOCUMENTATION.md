# API Documentation - Requests Module

## Overview
The Requests API provides RESTful endpoints for managing service requests within the FSM platform. All endpoints require authentication and return JSON responses.

## Base URL
```
Base URL: {site_url}/work-order-management/request
API Base: {site_url}/api/requests
```

## Authentication

### Session-Based Authentication
All API endpoints require a valid user session token passed via:
- **Session Cookie**: Standard web session
- **Auth Token**: Session token in request headers

```http
Authorization: Bearer {auth_token}
X-Requested-With: XMLHttpRequest
```

## Core Endpoints

### GET /work-order-management/request
**Purpose**: Retrieve requests listing page  
**Method**: GET  
**Authentication**: Required  

**Response**: HTML page with requests data

**Query Parameters**:
| Parameter | Type | Description | Default |
|-----------|------|-------------|----------|
| status | string | Filter by status | all |
| priority | string | Filter by priority | all |
| company_id | integer | Filter by company | all |
| search | string | Search term | empty |
| page | integer | Page number | 1 |
| limit | integer | Results per page | 25 |

**Example**:
```http
GET /work-order-management/request?status=pending&priority=high
```

### GET /work-order-management/request/view/{id}
**Purpose**: Display detailed request information  
**Method**: GET  
**Authentication**: Required  
**Parameters**: `id` (integer) - Request ID

**Response**: HTML detail view page

**Example**:
```http
GET /work-order-management/request/view/123
```