from flask import Flask, request, jsonify, send_file
from ultralytics import YOLO
from PIL import Image, ImageDraw
from io import BytesIO
import numpy as np
import base64

app = Flask(__name__)

model = YOLO('last.pt')

@app.route('/process-image', methods=['POST'])
def process_image():
    if 'image' not in request.files:
        return jsonify({'error': 'ไม่มีไฟล์ภาพ'}), 400

    image_file = request.files['image']
    image = Image.open(BytesIO(image_file.read())).convert('RGB')
    image_np = np.array(image)

    # ทำการพยากรณ์ภาพ
    results = model(image_np)
    detections = []

    # วาดกล่อง
    draw = ImageDraw.Draw(image)
    for result in results:
        for box in result.boxes:
            conf = box.conf.item()
            if conf < 0.50:
                continue

            x1, y1, x2, y2 = box.xyxy[0].tolist()  # ดึงค่าขอบเขตของกล่อง
            cls = box.cls.item()  # ดึงค่าประเภทของคลาส

            draw.rectangle([x1, y1, x2, y2], outline="red", width=3)
            draw.text((x1, y1), f"{model.names[int(cls)]} {conf:.2f}", fill="red")

            # ทำการ get ชื่อและจำนวน
            class_id = box.cls.item()
            class_name = result.names[class_id]
            detections.append((class_name, conf))

    detection_summary = {}
    for item, confidence in detections:
        if item in detection_summary:
            detection_summary[item]['count'] += 1
            detection_summary[item]['confidence'] += confidence
        else:
            detection_summary[item] = {'count': 1, 'confidence': confidence}        


    for name, data in detection_summary.items():
        print(name)
        print(data['count'])
        print(data['confidence'])


    # แปลงภาพกลับเป็น bytes เพื่อส่งกลับ
    img_byte_arr = BytesIO()
    image.save(img_byte_arr, format='PNG')
    img_byte_arr.seek(0)
    img_data = base64.b64encode(img_byte_arr.getvalue()).decode('utf-8')

    # ส่งทั้งภาพและข้อมูลการตรวจจับกลับ
    response = {
        'image': img_data,
        'detections': detection_summary
    }

    return jsonify(response)

    # return send_file(img_byte_arr, mimetype='image/png')

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5001, debug=True)