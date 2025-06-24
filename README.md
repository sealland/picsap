# Material Information System - Modernized

## Overview
This is a modernized version of the Material Information System with improved UI/UX and simplified parameter handling.

## Getting Started (สำหรับ GitHub)

### 1. Clone Project
```bash
git clone https://github.com/your-username/material-info-system.git
cd material-info-system
```

### 2. Setup
- ต้องใช้ PHP 7.4+ และ Web Server (IIS/Apache)
- ตั้งค่าไฟล์ `conn.php` ให้เชื่อมต่อฐานข้อมูลของคุณ
- ตรวจสอบ permission ของโฟลเดอร์ `material/` ให้ web server สามารถเขียน/ลบไฟล์ได้

### 3. File Structure
```
picsap/
├── index.php              # Main application file (modernized)
├── upload_handler.php     # Image upload processing
├── delete_image.php       # Image delete handler (with logging)
├── material/              # Image storage directory
│   └── [material_id]/     # Material-specific folders
├── log_delete_image.txt   # Log file for image deletion
├── conn.php               # Database connection config
├── README.md              # This documentation
```

### 4. Usage
- เปิดใช้งานผ่าน URL เช่น `index.php?id=ACAC00032`
- อัปโหลด/ลบรูปภาพได้ทันที (มีระบบยืนยันและ log)

### 5. Security & Production Notes
- ตรวจสอบสิทธิ์โฟลเดอร์ `material/` และ `log_delete_image.txt` ให้ปลอดภัย
- ไม่ควร commit ข้อมูลสำคัญ เช่น รหัสผ่านใน `conn.php` หรือ log จริงขึ้น GitHub (เพิ่มใน `.gitignore`)
- สามารถเพิ่ม `.env` หรือ config แยกสำหรับ production ได้

## Key Features
- Modern UI (Bootstrap 5, Font Awesome, Lightbox)
- Upload & Delete images (with confirmation and logging)
- Responsive design
- Security: file validation, input sanitization, directory traversal protection

## Support
หากมีข้อผิดพลาดหรือข้อสงสัย กรุณาติดต่อแผนกไอที 