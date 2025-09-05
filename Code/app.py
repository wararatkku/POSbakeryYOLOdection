import asyncio
import websockets
from ultralytics import YOLO
from PIL import Image, ImageDraw
from io import BytesIO
import numpy as np
import base64
import json

model = YOLO('last.pt')

async def process_image(websocket, path):
    async for message in websocket:
        image_data = base64.b64decode(message)
        image = Image.open(BytesIO(image_data)).convert('RGB')
        image_np = np.array(image)

        # ทำการพยากรณ์ภาพ
        results = model(image_np)
        detections = []

        # วาดกล่อง detect รูปภาพ
        draw = ImageDraw.Draw(image)
        for result in results:
            for box in result.boxes:
                conf = box.conf.item()  # ค่าความมั่นใจ
                if conf < 0.50:  
                    continue

                x1, y1, x2, y2 = box.xyxy[0].tolist()  
                cls = box.cls.item()  

                draw.rectangle([x1, y1, x2, y2], outline="red", width=3)
                draw.text((x1, y1), f"{model.names[int(cls)]} {conf:.2f}", fill="red")

                # เก็บค่าข้อมูลแต่ละคลาส
                class_id = box.cls.item()
                class_name = result.names[class_id]
                detections.append((class_name, conf))

        # นับจำนวน
        detection_summary = {}
        for item, confidence in detections:
            if item in detection_summary:
                detection_summary[item]['count'] += 1
                detection_summary[item]['confidence'] += confidence
            else:
                detection_summary[item] = {'count': 1, 'confidence': confidence}

        img_byte_arr = BytesIO()
        image.save(img_byte_arr, format='PNG')
        img_byte_arr.seek(0)
        img_base64 = base64.b64encode(img_byte_arr.getvalue()).decode('utf-8')

        # ส่งค่า response กลับไป
        response = {
            'image': img_base64,
        }

        await websocket.send(json.dumps(response))  # แปลงเป็น json

start_server = websockets.serve(process_image, "localhost", 8765)

asyncio.get_event_loop().run_until_complete(start_server)
asyncio.get_event_loop().run_forever()