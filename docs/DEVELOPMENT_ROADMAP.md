# FSM Development Roadmap & Starting Point

## Recommended Starting Point: MVP (Minimum Viable Product)

### Phase 1: Core Foundation (Weeks 1-4)
Start with these essential features that address your immediate needs:

#### 1. Database Schema & Models
- **Customers** (sync with Canvass Global camera owners)
- **Work Orders** (for installations and repairs)
- **Users** (field technicians)
- **Services** (camera installation, repair types)

#### 2. Authentication System
- Implement session-based auth matching Canvass Global
- Basic role system (Admin, Dispatcher, Field Tech)
- API authentication for Canvass Global integration

#### 3. Customer Management (Basic)
- Customer CRUD operations
- Sync with Canvass Global camera owners
- Basic search and filtering
- Customer location mapping

#### 4. Work Order Management (Essential)
- Create work orders for:
  - New camera installations
  - Camera repairs
  - Canvassing visits
- Basic status tracking (New, Assigned, In Progress, Completed)
- Assign to field technicians
- Basic scheduling calendar

### Phase 2: Field Operations (Weeks 5-8)
Add features that enhance field productivity:

#### 1. Mobile-Friendly Web Interface
- Responsive design for tablets/phones
- Work order details view
- Status updates from field
- Photo uploads from field

#### 2. Basic Reporting
- Daily work order summary
- Technician productivity
- Customer visit history

#### 3. Canvass Global Integration
- Real-time camera owner sync
- Update camera status after service
- Share service history

### Phase 3: Enhanced Features (Weeks 9-12)
Expand capabilities based on initial usage:

#### 1. Advanced Scheduling
- Map view for route optimization
- Multi-day appointments
- Recurring maintenance

#### 2. Parts & Inventory
- Basic parts tracking
- Parts used per work order

#### 3. Customer Communication
- Email notifications
- Service completion reports

## Why This Starting Point?

### 1. **Immediate Value**
- Addresses your core need: managing canvassing and service visits
- Integrates with existing Canvass Global data
- Provides basic field operation support

### 2. **Quick to Market**
- Can be operational in 4-6 weeks
- Uses existing CodeIgniter expertise
- Leverages Canvass Global authentication model

### 3. **Foundation for Growth**
- Clean architecture supports future features
- Database design accommodates all planned features
- API-first approach enables mobile app later

### 4. **Risk Mitigation**
- Validates core workflows early
- Gets user feedback quickly
- Proves integration concept

## Development Environment Setup

### Local Development
- **Server**: MAMP (already configured)
- **Project Path**: `/Users/anthony/Sites/fsm`
- **Local URL**: `http://localhost/fsm/`
- **Database**: SQLite in `/writable/database/fsm.db`

### Deployment Environment
- **Initial**: Namecheap Shared Hosting
  - Cost-effective for MVP phase
  - Supports CodeIgniter 4 and SQLite
  - Easy deployment via FTP/cPanel
- **Future**: VPS when user base grows

## Technical Starting Tasks

### Week 1: Setup & Foundation
```bash
# 1. Configure CodeIgniter environment
# 2. Set up SQLite database
# 3. Create base models and migrations
# 4. Implement authentication
# 5. Set up API structure
```

### Week 2: Core Models & APIs
```bash
# 1. Customer model & API
# 2. Work Order model & API
# 3. User model & API
# 4. Basic CRUD operations
# 5. Canvass Global integration endpoint
```

### Week 3: User Interface
```bash
# 1. Admin dashboard
# 2. Work order management screens
# 3. Customer list and details
# 4. Technician assignment interface
# 5. Basic calendar view
```

### Week 4: Integration & Testing
```bash
# 1. Complete Canvass Global sync
# 2. Test work order workflow
# 3. Field technician interface
# 4. Basic reporting
# 5. Deploy to development server
```

## Success Metrics for MVP

1. **Operational Efficiency**
   - Time to create and assign work order < 2 minutes
   - Field tech can update status in < 30 seconds
   - Customer data syncs within 5 minutes

2. **Integration Success**
   - 100% of Canvass Global camera owners accessible
   - Service history visible in both systems
   - No duplicate data entry required

3. **User Adoption**
   - All field techs using system daily
   - 90% of work orders tracked digitally
   - Positive feedback from field teams

## Next Steps

1. **Set up development environment**
2. **Create database schema**
3. **Build authentication system**
4. **Implement first API endpoint**
5. **Create basic UI framework**

This approach gets you operational quickly while building a solid foundation for the comprehensive FSM platform outlined in the PRD.
