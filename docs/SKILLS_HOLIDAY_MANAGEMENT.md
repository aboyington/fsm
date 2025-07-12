# Skills and Holiday Management Documentation

## Overview
The Skills and Holiday Management features provide comprehensive workforce capability tracking and business calendar management for the FSM platform. These features enable organizations to efficiently manage technician skills and plan operations around business holidays.

## Skills Management

### Purpose
Skills management allows organizations to track and manage the capabilities of their workforce, ensuring proper job assignments and maintaining service quality standards.

### Features

#### Core Functionality
- **Skill Creation**: Define custom skills relevant to your organization
- **Skill Categories**: Organize skills into logical groups (Technical, Soft Skills, Certifications, etc.)
- **Skill Levels**: Track proficiency levels for each skill
- **Status Management**: Active/Inactive skill tracking
- **Search & Filter**: Real-time search and filtering capabilities
- **Audit Trail**: Track skill creation and modifications

#### Skill Categories
- **Technical Skills**: Equipment-specific, software, troubleshooting
- **Soft Skills**: Communication, leadership, customer service
- **Certifications**: Professional certifications, licenses
- **Safety**: Safety protocols, equipment handling
- **Specialized**: Industry-specific or unique skills

#### Skill Levels
- **Beginner**: Basic understanding or introductory level
- **Intermediate**: Solid working knowledge
- **Advanced**: Expert level proficiency
- **Expert**: Master level with teaching capability

### Database Schema

#### Table: `skills`
```sql
CREATE TABLE skills (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category ENUM('technical', 'soft_skills', 'certifications', 'safety', 'specialized') DEFAULT 'technical',
    level ENUM('beginner', 'intermediate', 'advanced', 'expert') DEFAULT 'beginner',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);
```

### API Endpoints

#### Skills Management
- `GET /settings/skills` - List all skills with filtering
- `POST /settings/skills/add` - Create new skill
- `GET /settings/skills/get/{id}` - Get skill details
- `POST /settings/skills/update/{id}` - Update skill
- `POST /settings/skills/delete/{id}` - Delete skill

### Usage Guide

#### Creating a New Skill
1. Navigate to **Settings → Workforce → Skills**
2. Click the **"New Skill"** button
3. Fill in the skill information:
   - **Name**: Enter a descriptive skill name (required)
   - **Description**: Provide detailed description (optional)
   - **Category**: Select appropriate category
   - **Level**: Choose skill level
   - **Status**: Set to Active or Inactive
4. Click **"Add Skill"** to save

#### Managing Existing Skills
1. **Edit Skills**: Click the pencil icon in the Actions column
2. **Delete Skills**: Click the trash icon and confirm deletion
3. **Filter Skills**: Use the status filter to view Active/Inactive/All skills
4. **Search Skills**: Use the search bar to find specific skills

#### Best Practices
- Use consistent naming conventions
- Group related skills under appropriate categories
- Include detailed descriptions for complex skills
- Regularly review and update skill levels
- Deactivate obsolete skills instead of deleting them

---

## Holiday Management

### Purpose
Holiday management enables organizations to configure business holidays for scheduling, calendar planning, and resource allocation. It ensures proper planning around non-working days.

### Features

#### Core Functionality
- **Year-Based Management**: Configure holidays for specific years
- **Holiday Calendar**: View holidays in a clear table format
- **Flexible Editing**: Add, edit, and remove holidays easily
- **Multiple Years**: Support for configuring holidays across multiple years
- **Day Calculation**: Automatically calculate day of the week for holidays
- **Bulk Operations**: Manage multiple holidays efficiently

#### Holiday Information
- **Holiday Name**: Custom holiday names (e.g., "New Year's Day", "Company Annual Meeting")
- **Holiday Date**: Specific date for the holiday
- **Day Display**: Automatic day-of-week calculation
- **Year Management**: Separate holiday lists for different years

### Database Schema

#### Table: `holidays`
```sql
CREATE TABLE holidays (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    holiday_date DATE NOT NULL,
    year INT NOT NULL,
    description TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id),
    UNIQUE KEY unique_holiday_per_year (name, year)
);
```

### API Endpoints

#### Holiday Management
- `GET /settings/holiday` - Display holiday management page
- `POST /settings/holiday/save` - Save holidays for a specific year
- `GET /settings/holiday/get/{year}` - Get holidays for a specific year
- `POST /settings/holiday/update/{year}` - Update holidays for a specific year
- `POST /settings/holiday/delete/{id}` - Delete a specific holiday

### Usage Guide

#### Configuring Holidays
1. Navigate to **Settings → Workforce → Holiday**
2. Select the desired year from the dropdown (2023-2027)
3. Click the **"Edit"** button to modify holidays
4. In the Edit Modal:
   - **Year**: Confirm or change the year
   - **Holiday List**: Add or modify holiday entries
   - **Add Holidays**: Click "New Line" to add additional holidays
   - **Remove Holidays**: Click the minus (-) button to remove holidays

#### Adding New Holidays
1. In the Edit Modal, click **"New Line"**
2. Enter the holiday information:
   - **Holiday Name**: Enter descriptive name
   - **Holiday Date**: Select date using date picker
3. Repeat for additional holidays
4. Click **"Save"** to apply changes

#### Managing Holiday Lists
- **View Mode**: See all holidays for the selected year in a clean table
- **Edit Mode**: Modify the entire holiday list for a year
- **Year Navigation**: Switch between years to manage different annual calendars
- **Bulk Operations**: Add/remove multiple holidays in one session

#### Pre-configured Holidays
The system includes common holidays by default:
- New Year's Day (January 1)
- Independence Day (July 4)
- Christmas Day (December 25)

#### Best Practices
- Plan holidays at the beginning of each year
- Include both statutory and company-specific holidays
- Coordinate with HR for company-wide holiday policies
- Review and update holiday calendars annually
- Consider regional variations for multi-location organizations

---

## Technical Implementation

### Models

#### SkillModel
- Handles skill CRUD operations
- Validates skill data
- Manages skill categories and levels
- Tracks skill status changes

#### HolidayModel
- Manages holiday data by year
- Validates holiday dates
- Handles year-based filtering
- Supports bulk operations

### Controllers

#### Settings Controller
- Unified settings management
- Handles both Skills and Holiday routes
- Implements security measures
- Manages view rendering

### Views

#### Skills Interface
- `app/Views/settings/skills.php` - Main skills management interface
- Modal-based editing system
- Real-time filtering and search
- Responsive design for mobile access

#### Holiday Interface
- `app/Views/settings/holiday.php` - Holiday management interface
- Year-based holiday configuration
- Drag-and-drop-style editing
- Clear calendar view

### Security Features

#### Authentication & Authorization
- Session-based authentication required
- Role-based access control
- CSRF protection on all forms
- Input validation and sanitization

#### Data Protection
- XSS protection
- SQL injection prevention
- Input length validation
- Secure data handling

### Database Migrations

#### Skills Migration
- `2025-01-11-000006_CreateSkillsTable.php`
- Includes all necessary fields and relationships
- Proper indexing for performance

#### Holiday Migration
- `2025-01-11-000007_CreateHolidaysTable.php`
- Year-based indexing
- Unique constraints to prevent duplicates

---

## Integration Points

### User Management Integration
- Both Skills and Holidays link to user management
- Creator tracking for audit purposes
- User-based access control

### Settings Module Integration
- Seamless integration with existing settings
- Consistent UI/UX patterns
- Shared authentication and security

### Future Integrations
- **Scheduling System**: Holiday calendar integration
- **Employee Profiles**: Skill assignment to users
- **Reporting**: Skills and holiday reporting
- **Calendar Systems**: Export holiday calendars

---

## Troubleshooting

### Common Issues

#### Skills Management
- **Skills Not Saving**: Check form validation and required fields
- **Search Not Working**: Verify JavaScript is enabled
- **Category Issues**: Ensure category values match database enum

#### Holiday Management
- **Holidays Not Displaying**: Check year selection and data availability
- **Modal Not Opening**: Verify Bootstrap JavaScript is loaded
- **Date Format Issues**: Ensure proper date format (YYYY-MM-DD)

### Error Handling
- Form validation provides user-friendly error messages
- Server-side validation prevents invalid data
- Graceful degradation for JavaScript-disabled browsers

---

## Performance Considerations

### Database Optimization
- Proper indexing on frequently queried fields
- Efficient query patterns
- Minimal database calls per page load

### Frontend Performance
- Cached JavaScript and CSS
- Optimized modal loading
- Efficient DOM manipulation

### Scalability
- Designed for organizations with hundreds of skills
- Efficient year-based holiday management
- Minimal resource usage

---

## Maintenance and Updates

### Regular Tasks
- Review and update skill categories annually
- Maintain holiday calendars for upcoming years
- Monitor system performance and usage
- Update documentation as features evolve

### Backup Considerations
- Skills data should be included in regular backups
- Holiday configurations are critical for scheduling
- Test restore procedures for both datasets

This documentation provides comprehensive guidance for implementing and managing the Skills and Holiday features in your FSM system.
