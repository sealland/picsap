# Material Information System - Modernized

## Overview
This is a modernized version of the Material Information System with improved UI/UX and simplified parameter handling.

## Key Changes Made

### 1. Simplified URL Parameters
- **Before**: Complex URL with many parameters
  ```
  https://picsap.zubbsteel.com/index.php?id=ACAC00032&pr=&po=&mat_group=ACAC&mat_des=LOCK%20NUT%20KM16&requis=&plant=&name1=&name2=&name3=&name4=&name5=&sname=&price1=%20%20%20%20%20%20%200.00000&price2=%20%20%20%20%20%20%200.00000&price3=%20%20%20%20%20%20%200.00000&price4=%20%20%20%20%20%20%200.00000&price5=%20%20%20%20%20%20%200.00000&finalprice=%20%20%20%20%20%20%20%20%20%200.00
  ```
- **After**: Simple URL with only the ID parameter
  ```
  https://picsap.zubbsteel.com/index.php?id=ACAC00032
  ```

### 2. Modern UI Design
- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Modern Styling**: Gradient backgrounds, rounded corners, shadows, and animations
- **Bootstrap 5**: Latest Bootstrap framework for consistent styling
- **Font Awesome Icons**: Professional icons throughout the interface
- **Lightbox Gallery**: Click on images to view them in a lightbox overlay

### 3. Image Upload Functionality
- **Multiple File Upload**: Select and upload multiple images at once
- **File Validation**: Supports JPG, PNG, and GIF formats
- **Size Limits**: Maximum file size of 10MB per image
- **Automatic Directory Creation**: Creates material-specific folders automatically
- **Unique Filenames**: Prevents filename conflicts with timestamp-based naming

### 4. Enhanced Features
- **Material Information Display**: Clean, organized display of material details
- **Vendor Comparison**: Visual comparison of vendor prices
- **Error Handling**: Proper error messages and validation
- **Loading States**: Visual feedback during uploads
- **Mobile Responsive**: Optimized for all screen sizes

## File Structure
```
picsap/
├── index.php              # Main application file (modernized)
├── upload_handler.php     # Image upload processing
├── material/              # Image storage directory
│   └── [material_id]/     # Material-specific folders
└── README.md              # This documentation
```

## Usage

### Basic Usage
1. Access the system with a material ID:
   ```
   https://your-domain.com/index.php?id=ACAC00032
   ```

2. View material information and existing images

3. Upload new images using the upload form

### Image Upload
1. Click "Choose Files" in the upload section
2. Select one or more images (JPG, PNG, GIF)
3. Click "Upload Images"
4. Images will be saved to `material/[material_id]/` directory

## Technical Details

### Dependencies
- **Bootstrap 5.3.0**: CSS framework
- **Font Awesome 6.4.0**: Icon library
- **Lightbox 2.11.4**: Image gallery
- **PHP 7.4+**: Server-side processing

### Security Features
- File type validation
- File size limits
- Directory traversal protection
- Input sanitization
- Material ID validation

### Browser Support
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Migration Notes
- The system maintains backward compatibility with existing URL parameters
- Old URLs will still work but will only use the `id` parameter
- Existing images in the `material/` directory will continue to work
- No database changes required

## Support
หากมีข้อผิดพลาดหรือข้อสงสัย กรุณาติดต่อแผนกไอที 