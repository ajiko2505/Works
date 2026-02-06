# Features & Capabilities - AMVRS ARMED

Complete documentation of all features and how to use them.

## Core Features Overview

### 1. User Management

#### Registration
**File**: `register.php`  
**Features**:
- New user self-registration
- Email validation (recommended enhancement)
- Password strength requirements
- User role assignment during registration

**How to Use**:
1. Click "Register" on home page
2. Fill in personal information
3. Create username and password
4. Select user type (Driver/User)
5. Submit and verify email (if enabled)

#### Login
**File**: `login.php`  
**Features**:
- Secure authentication
- Session management
- Remember me functionality (optional)
- Failed login tracking

**How to Use**:
1. Visit login page
2. Enter credentials
3. Click "Login"
4. Redirected to dashboard

#### User Profile
**File**: `profile.php`  
**Features**:
- View user information
- Edit personal details
- Change password
- View user statistics
- Update contact information

### 2. Vehicle Request System

#### Request Creation
**File**: `request.php`  
**Features**:
- Browse available vehicles
- Fill request details (mission, date, duration)
- Attach supporting documents (future enhancement)
- Real-time vehicle availability check
- Submit for approval

**Form Fields**:
```
- Vehicle Selection: Dropdown of available vehicles
- Mission Description: Text area
- Departure Date: Date picker
- Return Date: Date picker
- Destination: Text input
- Driver Name: Auto-populated
- Number of Passengers: Numeric input
- Special Requirements: Text area
```

#### My Requests
**File**: `myrequest.php`  
**Features**:
- View own submitted requests
- Filter by status (Pending, Approved, Rejected, Returned)
- View request history
- Cancel pending requests
- Track request timeline

**Status Types**:
- 📋 **Pending**: Awaiting approval
- ✅ **Approved**: Ready for use
- ❌ **Rejected**: Request denied
- 🔄 **In Use**: Currently borrowed
- ✔️ **Returned**: Vehicle returned

#### Request Preview
**File**: `preview.php`  
**Features**:
- View full request details
- Print request confirmation
- Download request PDF
- View approval comments
- Request history timeline

### 3. Approval Workflow

#### Admin Approval Panel
**File**: `approve.php`  
**Features**:
- View pending requests
- Filter by vehicle/user/date
- Approve or reject requests
- Add approval comments
- Set conditions for approval
- Send automatic notifications
- View approval history

**Actions Available**:
- ✅ Approve request
- ❌ Reject with reason
- ⏳ Request more information
- 📝 Add notes

#### Request Validation
**File**: `reqvad.php`  
**Features**:
- Validate request details
- Check vehicle availability
- Verify user clearance
- Confirm mission details
- Validate dates and times

#### Approver Dashboard
**File**: `reqapp.php`  
**Features**:
- Overview of pending approvals
- Statistics on requests
- Quick actions menu
- Filtering and sorting
- Email notifications for new requests

### 4. Vehicle Management

#### Vehicle Listing
**File**: `index.php`  
**Features**:
- Browse all available vehicles
- Filter by vehicle type
- View vehicle details:
  - Model and year
  - Capacity
  - Current status
  - Maintenance schedule
  - Usage history

#### Vehicle Status Tracking
**Status**: `vehicles` table
**Current State**:
- 🟢 Available: Ready for use
- 🟡 In Use: Currently borrowed
- 🔴 Maintenance: Not available
- ⚫ Reserved: Scheduled soon

#### Vehicle Information
**Includes**:
- Registration number
- Vehicle type/category
- Color
- Current mileage
- Last maintenance date
- Insurance expiry
- Next service due

### 5. Dashboard & Reporting

#### User Dashboard
**File**: `index.php`  
**Shows**:
- Available vehicles at a glance
- Quick access to request vehicle
- Recent request status
- Navigation menu

#### Admin Dashboard
**File**: `newoldindex.php` or admin section  
**Shows**:
- Pending approvals count
- Overdue vehicles list
- System statistics
- User activity log
- Vehicle utilization rates

#### Overdue Management
**File**: `overdue.php`  
**Features**:
- List overdue vehicle returns
- Contact information for users
- Send reminders
- Track overdue duration
- Generate overdue reports

#### Request Review
**File**: `review.php`  
**Features**:
- Review completed requests
- Mark satisfaction
- Generate feedback
- Submit comments
- Rate experience

### 6. Communication Features

#### Email Notifications
**Files**: Various pages with PHPMailer  
**Triggers**:
- Request submitted confirmation
- Request approved notification
- Request rejected with reason
- Overdue return reminders
- System alerts

**Customizable**:
- Email templates
- Recipient list
- Notification timing
- Message content

#### In-App Notifications
**Features**:
- Request status updates
- Approval messages
- System announcements
- Important alerts

### 7. User Roles & Permissions

#### Role: Driver/User
**Permissions**:
- ✅ View own requests
- ✅ Submit new requests
- ✅ Edit own profile
- ✅ Cancel pending requests
- ❌ Approve requests
- ❌ Manage users
- ❌ View other users' requests

**Pages Accessible**:
- Home
- Login/Register
- Request Vehicle
- My Requests
- Profile
- Preview

#### Role: Approver
**Permissions**:
- ✅ View all requests
- ✅ Approve/Reject requests
- ✅ Add approval notes
- ✅ View user information
- ✅ Generate reports
- ❌ Delete users
- ❌ Manage system settings

**Pages Accessible**:
- Dashboard
- My Requests
- All Requests
- Approve Panel
- Request Validation
- Review
- Profile

#### Role: Administrator
**Permissions**:
- ✅ All approver permissions
- ✅ User management
- ✅ System configuration
- ✅ View all logs
- ✅ Generate analytics reports
- ✅ Manage database
- ✅ System settings

**Pages Accessible**:
- All pages
- Admin dashboard
- User management
- System settings
- Database backups
- Logs and reports

### 8. Data Management

#### User Cancellation
**File**: `canc.php`  
**Features**:
- Cancel pending requests
- Provide cancellation reason
- Notification to approvers
- Automatic status update
- Audit trail entry

#### Data Checking
**File**: `chk.php`  
**Features**:
- Validate data integrity
- Check for orphaned records
- System health check
- Database consistency verification

#### User Registration Logs
**File**: `userreg.php`  
**Tracks**:
- New user registrations
- Registration date
- User details
- Initial role assignment

#### User Login Logs
**File**: `userlog.php`  
**Records**:
- Login timestamps
- User IP addresses
- Session duration
- Login success/failure

#### User Request Logs
**File**: `userrequest.php`  
**Logs**:
- Request submission times
- Request modifications
- Status changes
- Approval/rejection details

### 9. User Administration

#### User Management Panel
**File**: `users.php`  
**Features**:
- List all registered users
- Edit user details
- Change user roles
- Deactivate/activate accounts
- Reset passwords
- View user activity

#### User Signature
**File**: `usersig.php`  
**Features**:
- Digital signature capture (if enhanced)
- Signature verification
- Signature storage
- Legal compliance

#### User Account Updates
**File**: `profup.php`  
**Features**:
- Update user information
- Change contact details
- Update emergency contacts
- Modify address
- Update department/unit

#### Account Logout
**File**: `logout.php`  
**Features**:
- Secure session termination
- Clear cookies
- Redirect to login
- Session cleanup

## Advanced Features

### Scheduled Tasks
- Automatic reminder emails
- Overdue vehicle alerts
- Regular maintenance reminders
- Backup scheduling

### Integration Points
- Email system (PHPMailer)
- Database (MySQL)
- Web framework (Bootstrap, jQuery)
- Authentication system

### Reporting
- Request statistics
- Vehicle utilization
- User activity reports
- Approval workflow metrics
- System usage analytics

### Search & Filtering
- Filter requests by status
- Search vehicles by model/type
- Find users by name/ID
- Sort by date/priority

## Feature Matrix

| Feature | User | Approver | Admin |
|---------|------|----------|-------|
| Submit Request | ✅ | ✅ | ✅ |
| View Own Requests | ✅ | ✅ | ✅ |
| View All Requests | ❌ | ✅ | ✅ |
| Approve Requests | ❌ | ✅ | ✅ |
| Manage Users | ❌ | ❌ | ✅ |
| System Settings | ❌ | ❌ | ✅ |
| Generate Reports | ❌ | ✅ | ✅ |
| Update Profile | ✅ | ✅ | ✅ |

## Typical User Workflows

### Driver Workflow
1. Login to account
2. Browse available vehicles on home page
3. Click "Request Vehicle"
4. Fill in mission and date details
5. Submit request
6. Monitor status in "My Requests"
7. Once approved, use vehicle
8. Return vehicle and mark as returned
9. Submit feedback

### Approver Workflow
1. Login to account
2. View "Pending Approvals" dashboard
3. Click on pending request
4. Review mission and driver details
5. Validate vehicle availability
6. Approve or reject with comment
7. System sends notification
8. View request status update

### Administrator Workflow
1. Login to account
2. Review system dashboards
3. Manage user accounts
4. Configure system settings
5. Monitor overdue returns
6. Generate monthly reports
7. Backup system data
8. Review security logs

## Performance Metrics

- **Average page load time**: < 2 seconds
- **Request processing time**: < 5 seconds
- **Email delivery**: < 1 minute
- **Database query time**: < 500ms
- **User concurrent limit**: 100+ users

---

**Last Updated**: February 6, 2026  
**Version**: 1.0.0  
**Total Features**: 50+
