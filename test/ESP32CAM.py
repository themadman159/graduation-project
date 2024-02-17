import cv2
from pyzbar.pyzbar import decode
import numpy as np
import urllib.request

# เปิดอุปกรณ์การจับภาพ (เช่น กล้องเว็บแคม)
#cap = cv2.VideoCapture(0)
url='http://172.20.10.2/'
#cv2.namedWindow("live transmission", cv2.WINDOW_AUTOSIZE)

while True:
    img_resp=urllib.request.urlopen(url+'cam-hi.jpg')
    imgnp=np.array(bytearray(img_resp.read()),dtype=np.uint8)
    frame=cv2.imdecode(imgnp,-1)
    # อ่านเฟรมจากอุปกรณ์การจับภาพ
    #ret, frame = cap.read()

    # สแกนบาร์โค้ดในเฟรม
    barcodes = decode(frame)

    # วนลูปผ่านบาร์โค้ดที่ตรวจพบและพิมพ์ข้อมูลของพวกเขา
    for barcode in barcodes:
        barcode_data = barcode.data.decode('utf-8')
        print(f"Barcode Type: {barcode.type}, Data: {barcode_data}")

        # วาดสี่เหลี่ยมรอบบาร์โค้ด
        points = barcode.polygon
        if len(points) > 4:
            hull = cv2.convexHull(np.array([point for point in points], dtype=np.float32))
            cv2.polylines(frame, [hull], True, (0, 255, 0), 2)

    # แสดงเฟรม
    cv2.imshow('Barcode Scanner', frame)

    # ออกจากโปรแกรมเมื่อกดปุ่ม 'q'
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

# ปล่อยอุปกรณ์การจับภาพและปิดหน้าต่าง OpenCV
cv2.destroyAllWindows()

