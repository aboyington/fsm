# Attachments Tab Implementation Guide

## Overview

The `Attachments` tab provides comprehensive functionality for managing file uploads within each request. This system includes file upload, storage, preview, download, and deletion capabilities with a modern, user-friendly interface. This guide provides complete implementation details to facilitate replicating this functionality in other modules.

## Architecture

### Frontend Components
- **Location**: `app/Views/requests/view.php` (Attachments tab section)
- **Empty State**: Visual mockup with "Add Attachment" button when no files are present
- **File Input**: Hidden HTML input with drag-and-drop support
- **Dynamic Rendering**: JavaScript-powered attachment cards with action buttons
- **Preview Modal**: Bootstrap modal for image and PDF preview

### Backend Components
- **Model**: `app/Models/AttachmentModel.php` - Database interactions and file management
- **Controller**: `app/Controllers/Api/AttachmentsController.php` - API endpoints for all operations
- **Migration**: Database schema for attachments table with foreign key relationships
- **Storage**: Organized file system in `writable/uploads/[request_number]/`

### API Endpoints
- `GET /api/requests/{id}/attachments` - List all attachments for a request
- `POST /api/requests/{id}/attachments/upload` - Upload new files
- `GET /api/requests/{id}/attachments/{attachmentId}/download` - Download file
- `GET /api/requests/{id}/attachments/{attachmentId}/preview` - Preview supported files
- `DELETE /api/requests/{id}/attachments/{attachmentId}` - Delete attachment

## Features

### 1. Empty State Interface
- **Visual Design**: Custom illustrated empty state with floating elements
- **Call-to-Action**: Prominent "Add Attachment" button
- **User Guidance**: Descriptive text explaining drag-and-drop functionality
- **Responsive**: Adapts to different screen sizes

### 2. File Upload System
- **Multiple Methods**: 
  - Click "Add Attachment" button to open file picker
  - Drag and drop files directly onto the interface
- **Multi-file Support**: Upload multiple files simultaneously
- **Progress Indication**: Visual spinner during upload process
- **Validation**: File size (10MB max) and type restrictions
- **Error Handling**: User-friendly error messages for failed uploads

### 3. File Management Interface
- **Compact List Layout**: Space-efficient horizontal list display replacing card grid
- **File Information**: Name, size, upload date, and uploader details in inline format
- **Visual Icons**: File type-specific Bootstrap icons (images, PDFs, documents, etc.)
- **Action Buttons**: Preview, Download, and Delete with tooltips and compact sizing
- **Hover Effects**: Subtle background color transitions on item interaction
- **Responsive Design**: Mobile-optimized with vertical stacking on smaller screens

### 4. Preview Functionality ⭐ NEW
- **Supported Types**: 
  - **Images**: JPEG, PNG, GIF, WebP, BMP
  - **Documents**: PDF files
- **Modal Interface**: Large, centered modal with responsive sizing
- **Image Preview**: Optimized display with max 70vh height, maintaining aspect ratio
- **PDF Preview**: Embedded iframe viewer with fallback link
- **Quick Actions**: Download button available within preview modal
- **Security**: Server-side validation and secure file serving

### 5. Storage Architecture
- **Organized Structure**: Files stored in `writable/uploads/[REQUEST_NUMBER]/`
- **Unique Naming**: Automatic handling of duplicate file names
- **Database Tracking**: Complete metadata storage including:
  - Original filename and system filename
  - File size and MIME type
  - Upload timestamp and user information
  - Request association via foreign keys

### 6. Pagination System ⭐ NEW
- **Automatic Pagination**: Displays when more than 20 attachments are present
- **Items Per Page**: Configurable limit (default: 20 attachments per page)
- **Smart Navigation**: 
  - Previous/Next buttons with disabled states
  - Page number display (up to 5 visible pages)
  - Ellipsis indication for large page ranges
  - First/Last page quick access
- **Dual Placement**: Pagination controls appear both above and below attachment list
- **Responsive Controls**: Touch-friendly buttons and proper spacing
- **State Persistence**: Maintains current page during operations (upload, delete)

### 7. Compact Layout Design ⭐ NEW
- **Space Efficiency**: Horizontal list layout replaces card grid for better density
- **Visual Hierarchy**: 
  - Large file type icons (1.5rem) for quick identification
  - Prominent file names with truncation and tooltips
  - Inline metadata with contextual icons
- **Information Display**:
  - File size with storage icon
  - Upload date with calendar icon  
  - Uploader name with person icon
- **Action Buttons**: Compact small buttons (`btn-sm`) with reduced padding
- **Mobile Optimization**: Vertical stacking of information on small screens

### 8. Security Features
- **Authentication**: All endpoints require user authentication
- **File Validation**: Server-side checks for file type and size
- **Access Control**: Users can only access attachments for requests they have permission to view
- **Safe File Handling**: Proper sanitization of file names and paths

## Database Schema

```sql
CREATE TABLE attachments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT UNSIGNED NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    file_path TEXT NOT NULL,
    file_size BIGINT UNSIGNED NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    uploaded_by INT UNSIGNED NOT NULL,
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    deleted_at DATETIME DEFAULT NULL,
    FOREIGN KEY (request_id) REFERENCES requests(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_request_id (request_id),
    INDEX idx_uploaded_by (uploaded_by)
);
```

## Implementation Guide

### Step 1: Database Setup
1. Run the attachments table migration:
   ```bash
   php spark migrate
   ```
2. Verify the `writable/uploads/` directory exists and is writable

### Step 2: Backend Implementation
1. **Create Model** (`app/Models/AttachmentModel.php`):
   - Extend CodeIgniter's Model class
   - Implement methods: `getAttachmentsByRequest()`, `deleteAttachment()`
   - Add validation rules for file operations

2. **Create API Controller** (`app/Controllers/Api/AttachmentsController.php`):
   - Implement all CRUD operations
   - Add file validation and security checks
   - Handle file storage and cleanup
   - Implement preview functionality with proper headers

3. **Update Routes** (`app/Config/Routes.php`):
   ```php
   $routes->get('requests/(:num)/attachments', 'Api\AttachmentsController::index/$1');
   $routes->post('requests/(:num)/attachments/upload', 'Api\AttachmentsController::upload/$1');
   $routes->get('requests/(:num)/attachments/(:num)/download', 'Api\AttachmentsController::download/$1/$2');
   $routes->get('requests/(:num)/attachments/(:num)/preview', 'Api\AttachmentsController::preview/$1/$2');
   $routes->delete('requests/(:num)/attachments/(:num)', 'Api\AttachmentsController::delete/$1/$2');
   ```

### Step 3: Frontend Implementation
1. **HTML Structure**: Add attachment tab content in your view file
2. **CSS Styles**: Include styles for:
   - Compact attachment list items and hover effects ⭐ NEW
   - Pagination controls and responsive behavior ⭐ NEW
   - Upload progress indicators
   - Modal responsiveness
   - Mobile optimization for small screens

3. **JavaScript Functions**:
   ```javascript
   // Core Functions
   - triggerFileUpload() - Opens file picker
   - handleFileUpload() - Processes selected files
   - loadAttachments() - Fetches and displays attachments
   - renderAttachments() - Creates compact list HTML with pagination
   
   // Pagination Functions ⭐ NEW
   - renderAttachmentPagination() - Generates pagination controls HTML
   - changeAttachmentsPage() - Handles page navigation
   - attachmentsPagination - Global pagination state object
   
   // Preview Functions
   - isPreviewable() - Checks if file type supports preview
   - previewAttachment() - Opens preview modal
   
   // Utility Functions
   - downloadAttachment() - Initiates file download
   - deleteAttachment() - Removes attachment with confirmation
   - getFileIcon() - Returns appropriate icon for file type
   - formatFileSize() - Converts bytes to readable format
   ```

### Step 4: Testing Checklist
- [ ] File upload (single and multiple)
- [ ] File preview (images and PDFs)
- [ ] File download
- [ ] File deletion
- [ ] Pagination navigation (when >20 attachments) ⭐ NEW
- [ ] Compact list layout display ⭐ NEW
- [ ] Mobile responsive behavior ⭐ NEW
- [ ] Page state persistence during operations ⭐ NEW
- [ ] Permission checks
- [ ] Error handling
- [ ] Responsive design
- [ ] Browser compatibility

## File Type Support

### Uploadable File Types
- **Documents**: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, RTF
- **Images**: JPG, JPEG, PNG, GIF, BMP, WebP, SVG
- **Archives**: ZIP, RAR, 7Z
- **Media**: MP4, AVI, MOV, WMV, MP3, WAV, AAC
- **Code**: HTML, CSS, JS, PHP, PY

### Preview-Supported Types
- **Images**: JPG, JPEG, PNG, GIF, WebP, BMP
- **Documents**: PDF

## Customization Options

### Extending File Type Support
1. Update `validateFile()` method in AttachmentsController
2. Add new file icons in `getFileIcon()` JavaScript function
3. Update preview support in `isPreviewable()` function

### Styling Customization
- Modify CSS classes for attachment cards
- Update empty state illustration
- Customize modal appearance
- Adjust responsive breakpoints

### Pagination Customization ⭐ NEW
- **Items Per Page**: Modify `itemsPerPage` in `attachmentsPagination` object (default: 20)
- **Pagination Threshold**: Change when pagination appears (currently >20 items)
- **Page Display Count**: Adjust maximum visible page numbers (default: 5 pages)
- **Button Styling**: Customize pagination button appearance and spacing
- **Auto-Scroll**: Add smooth scrolling to top when changing pages

### Storage Customization
- Change upload directory structure
- Implement cloud storage integration
- Add file compression options
- Implement file versioning

## Troubleshooting

### Common Issues
1. **Upload Failures**: Check file permissions on `writable/uploads/`
2. **Preview Not Working**: Verify MIME type detection and browser support
3. **Large Files**: Adjust PHP `upload_max_filesize` and `post_max_size`
4. **Missing Files**: Check file path construction and storage location

### Debug Tips
- Enable CodeIgniter logging for detailed error messages
- Check browser console for JavaScript errors
- Verify API responses using developer tools
- Test file permissions and directory structure

This implementation provides a robust, user-friendly file management system that can be easily adapted for use in other modules throughout your application.
