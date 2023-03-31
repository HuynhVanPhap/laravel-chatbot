[] Setup botman
    [1] install botman : composer require botman/botman
    [2] install botman driver : composer require botman/driver-web
    [3] touch config/botman/config.php
    [4] touch config/botman/web.php
    [5] link widget

[] Request simple cycle
    [1] Người dùng nhập đoạn text
    [2] Đoạn text sẽ được gởi đi theo thuộc tính 'chatServer' của web widget
    [3] Route Laravel chuyển qua controller để xử lý (BotManController@handle)
    [4] web widget nhận dữ liệu rồi hiển thị
