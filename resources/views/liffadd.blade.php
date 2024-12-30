<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Add Friend</title>
    <script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
</head>
<body>
    <div style="text-align: center; margin-top: 50px;">
        <h1>เพิ่มเพื่อนอัตโนมัติ</h1>
        <p>กดปุ่มเพื่อเพิ่มเราเป็นเพื่อนใน LINE</p>
        <button id="addFriendButton" style="padding: 10px 20px; font-size: 16px; background-color: #06C755; color: white; border: none; border-radius: 5px; cursor: pointer;">
            เพิ่มเพื่อนอัตโนมัติ
        </button>
        <p id="statusMessage" style="margin-top: 20px; color: green;"></p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const liffId = '2006463554-aWYA422R'; // ใส่ LIFF ID ของคุณ

            // Initialize LIFF
            liff.init({ liffId })
                .then(() => {
                    console.log('LIFF Initialized');
                })
                .catch((err) => {
                    console.error('LIFF Initialization failed', err);
                });

            document.getElementById('addFriendButton').addEventListener('click', async () => {
                try {
                    // Add Friend Automatically
                    await liff.friendship.add();
                    document.getElementById('statusMessage').textContent = 'เพิ่มเพื่อนเรียบร้อยแล้ว!';
                } catch (error) {
                    console.error('Error adding friend:', error);
                    document.getElementById('statusMessage').textContent = 'เกิดข้อผิดพลาดในการเพิ่มเพื่อน';
                }
            });
        });
    </script>
</body>
</html>
