<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Friend</title>
    <script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
</head>
<body>
    <div style="text-align: center; margin-top: 50px;">
        <h1>เพิ่มเราเป็นเพื่อนใน LINE</h1>
        <button id="addFriendButton" style="padding: 10px 20px; font-size: 16px; background-color: #06C755; color: white; border: none; border-radius: 5px; cursor: pointer;">
            เพิ่มเพื่อน
        </button>
    </div>

    <script>
        // LIFF Initialization
        document.addEventListener('DOMContentLoaded', () => {
            const liffId = '2006463554-aWYA422R'; // ใส่ LIFF ID ที่คุณได้จาก LINE Developers

            liff.init({ liffId }).then(() => {
                console.log('LIFF Initialized');
            }).catch((err) => {
                console.error('LIFF Initialization failed', err);
            });

            document.getElementById('addFriendButton').addEventListener('click', () => {
                // ใช้ addFriend API
                liff.openWindow({
                    url: 'https://line.me/R/ti/p/@kuanim_tungpichai', // แทนที่ YOUR_LINE_ID ด้วย LINE Official ID ของคุณ
                    external: true
                });
            });
        });
    </script>
</body>
</html>
