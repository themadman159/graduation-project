#include <ESP8266WiFi.h>        //ประกาศใช้ Library ของ ESP8266WIFI
#include <ESP8266HTTPClient.h>  //ประกาศใช้ Library ของ ESP8266HTTPClient.h
#include <LiquidCrystal_I2C.h>  //ประกาศใช้ Library LiquidCrystal_I2C เป็นไลบรารีที่ใช้ในการควบคุม LCD
#include <SoftwareSerial.h>     //ประกาศใช้ Library SoftwareSerial ใช้ในการสร้างพอร์ตซีเรียล
#include <ArduinoJson.h>        //ประกาศใช้ Library ArduinoJson ใช้ในการจัดการข้อมูลที่เป็น JSON
#include <Dictionary.h>         //ประกาศใช้ Library  Dictionary ใช้ในการสร้างโครงสร้างข้อมูลเป็นลิสต์ของคีย์-ค่า (key-value pairs) ที่ช่วยในการจัดเก็บและดึงข้อมูล
#include <WiFiClient.h>         //ประกาศใช้ Library WiFiClient ใช้ในการเชื่อมต่อกับเซิร์ฟเวอร์ผ่านโปรโตคอล TCP/IP ในโหมด WiFi

unsigned long buttonPressStartTime = 0;                   //ประกาศตัวแปรใช้เพื่อเก็บเวลาที่เริ่มต้นการกดปุ่ม (button press) ในหน่วยเวลาของ Arduino
const unsigned long BUTTON_CONFIRMATION_DURATION = 3000;  //เวลาที่ระบบจะต้องรอให้แน่ใจว่าปุ่มถูกกดค้างไว้นานเพียงพอก่อนที่จะตัดสินใจว่าการกดปุ่มถูกยืนยันหรือไม่ ในที่นี้คือ 3000 milliseconds หรือ 3 วินาที.

//กำหนดBTN_INCRE_PIN,BTN_DECRE_PIN,BTN_LEFT_PIN,BTN_RIGHT_PIN,BTN_RESET_PIN แล้วกำหนดขาD5,D6,D7,D8,D0ให้กับปุ่ม
#define BTN_INCRE_PIN D5
#define BTN_DECRE_PIN D6
#define BTN_LEFT_PIN D7
#define BTN_RIGHT_PIN D8
#define BTN_RESET_PIN D0

WiFiClient client;  //ประกาศตัวแปรชื่อ client ที่มีชนิดข้อมูลเป็น WiFiClient. ตัวแปร client นี้ถูกใช้ในการเชื่อมต่อกับเซิร์ฟเวอร์ผ่านโปรโตคอล TCP/IP ในโหมด WiFi บน Arduino

// const char* ssid = "yourwifi"; //ประกาศตัวแปร ssid  ชื่อของเครือข่าย WiFi ที่ต้องการให้ Arduino เชื่อมต่อ
// const char* password = "yourwifipassword"; //ประกาศตัวแปร password คือรหัสผ่านของเครือข่าย WiFi

//กำหนดค่าเริ่มต้นให้LiquidCrystal_I2C เพื่อควบคุมหน้าจอ LCD
LiquidCrystal_I2C lcd(0x27, 20, 4);
//กำหนดสื่อสารทางซีเรียลผ่านขาดิจิตอลที่ไม่ใช่ขาซีเรียลฮาร์ดแวร์ 3: ขา RX (รับข้อมูล) 1: ขา TX (ส่งข้อมูล)
SoftwareSerial mySerial(3, 1);
//กำหนดค่าเริ่มต้นของ status ให้มีค่าเป็น 0
int status = 0;

struct Product {
  String code;  //เก็บรหัสสินค้าแบบ String
  String name;  //เก็บชื่อสินค้าแบบ String
  int price;    //เก็บราคาสินค้าแบบจำนวนเต็ม
  int count;    //เก็บจำนวนสินค้าแบบจำนวนเต็ม
};

Product list[1000];    //ประกาศตัวแปรProductและlist นาดของอาร์เรย์นี้คือ 1000 ชิ้น
int list_current = 0;  //ประกาศตัวแปร list_current กำหนดค่าเริ่มต้นให้เป็น 0
int list_display = 0;  //ประกาศตัวแปร list_display กำหนดค่าเริ่มต้นให้เป็น 0 เป็นตัวแปรที่ใช้เก็บตำแหน่งของการแสดงผล (display) ในรายการ list

bool updated = false;            //ประกาศประเภทของตัวแปรเป็น boolean ชื่อ updated กำหนดค่าเริ่มต้นให้ updated เป็น false เพื่อบอกว่าข้อมูลได้รับการอัปเดตล่าสุดหรือไม่ ถ้า updated เป็น true แสดงว่ามีการอัปเดตล่าสุด, ถ้าเป็น false แสดงว่าไม่มีการอัปเดต
bool BTN_RESET_Pressed = false;  ////ประกาศประเภทของตัวแปรเป็น boolea ชื่อ BTN_RESET_Pressed กำหนดค่าเริ่มต้นให้ BTN_RESET_Pressed เป็น falseเพื่อตรวจสอบว่าปุ่ม RESET ของระบบหรืออุปกรณ์ได้รับการกดหรือไม่ ถ้า BTN_RESET_Pressed เป็น true แสดงว่าปุ่ม RESET ได้รับการกด, ถ้าเป็น false แสดงว่าปุ่ม RESET ไม่ได้รับการกด

//เริ่มฟังก์ชัน void setup()
void setup() {
  lcd.begin();      //ถูกเรียกเพื่อเริ่มต้นการใช้งาน LCD
  lcd.backlight();  //ถูกใช้เพื่อเปิดใช้งาน Backlight ของ LCD ซึ่งเป็นการเปิดหรือปิดแสง

  pinMode(BTN_INCRE_PIN, INPUT);  //ชื่อ ขา (pin) ที่เชื่อมต่อกับปุ่มที่เพิ่ม (increment) หรือปุ่ม "+" กำหนดให้ขานี้เป็นขานำเข้า (input) เพื่ออ่านค่าจากปุ่ม
  pinMode(BTN_DECRE_PIN, INPUT);  //ชื่อขา (pin) ที่เชื่อมต่อกับปุ่มที่ลด (decrement) หรือปุ่ม "-"กำหนดให้ขานี้เป็นขานำเข้า (input) เพื่ออ่านค่าจากปุ่ม
  pinMode(BTN_LEFT_PIN, INPUT);   //ชื่อขา (pin) ที่เชื่อมต่อกับปุ่มที่เลื่อนไปทางซ้าย กำหนดให้ขานี้เป็นขานำเข้า (input) เพื่ออ่านค่าจากปุ่ม
  pinMode(BTN_RIGHT_PIN, INPUT);  //ชื่อขา (pin) ที่เชื่อมต่อกับปุ่มที่เลื่อนไปทางขวา กำหนดให้ขานี้เป็นขานำเข้า (input) เพื่ออ่านค่าจากปุ่ม
  pinMode(BTN_RESET_PIN, INPUT);  //ชื่อของขา (pin) ที่เชื่อมต่อกับปุ่ม RESET กำหนดให้ขานี้เป็นขานำเข้า (input) เพื่ออ่านค่าจากปุ่ม RESET

  mySerial.begin(9600);  //ระบุจำนวนบิตต่อวินาทีที่ใช้ในการสื่อสารระหว่างอุปกรณ์9600 ในที่นี้คืออัตราเฟรมที่สามารถเข้าใจกันได้ทั้งส่งและรับข้อมูลระหว่าง Arduino กับอุปกรณ์ที่เชื่อมต่อแบบซีเรียล

  WiFi.begin(ssid, password);  //ใช้เพื่อเริ่มต้นการเชื่อมต่อกับเครือข่าย Wi-Fi โดยให้ระบุชื่อของเครือข่าย (SSID) และรหัสผ่าน (password)

  //ลูป while ที่ทำงานไปเรื่อย ๆ จนกว่า Arduino จะเชื่อมต่อกับเครือข่าย Wi-Fi สำเร็จ (WL_CONNECTED คือค่าคงที่ที่บอกว่าเชื่อมต่อสำเร็จ)
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    lcd.setCursor(0, 0);
    lcd.print("Connecting to WiFi");
  }
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("CONNECTING SUCCES");
  lcd.setCursor(0, 2);          //กำหนดตำแหน่งของ cursor บน LCD ที่ตำแหน่งแถวที่ 1, ตำแหน่งคอลัมน์ที่ 0
  lcd.print("READY TO SCAN!");  //แสดงข้อความ "OK!" บน LCD
}

//เริ่มฟังก์ชัน void lcdPrintTotal() เพื่อแสดงข้อมูลทั้งหมดที่เกี่ยวข้องกับรายการสินค้าบน LCD
void lcdPrintTotal() {
  int count = 0;                            //ประกาศตัวแปรที่ใช้เก็บจำนวนรวมของสินค้าทั้งหมดในรายการ
  int sum = 0;                              //ประกาศตัวแปรที่ใช้เก็บราคารวมของสินค้าทั้งหมดในรายการ
  for (int i = 0; i < list_current; i++) {  //ลูป for ที่ใช้สำหรับนับจำนวนรวมและราคารวมของสินค้าในรายการ
    sum += list[i].price * list[i].count;
    count += list[i].count;
  };

  lcd.setCursor(0, 2);   //กำหนดตำแหน่ง cursor บน LCD ที่แถวที่ 2, ตำแหน่งคอลัมน์ที่ 0
  lcd.print("Total");    //แสดงข้อความ "Total" บน LCD
  lcd.setCursor(11, 3);  //กำหนดตำแหน่ง cursor บน LCD ที่แถวที่ 3, ตำแหน่งคอลัมน์ที่ 11
  lcd.print(sum);        //แสดงค่าของ sum (ราคารวม) บน LCD
  lcd.setCursor(0, 3);   //กำหนดตำแหน่ง cursor บน LCD ที่แถวที่ 3, ตำแหน่งคอลัมน์ที่ 0
  lcd.print("Qt.");      //แสดงข้อความ "Qt." (ย่อมาจาก Quantity) บน LCD
  lcd.setCursor(3, 3);   //กำหนดตำแหน่ง cursor บน LCD ที่แถวที่ 3, ตำแหน่งคอลัมน์ที่ 3
  lcd.print(count);      // แสดงค่าของ count (จำนวนรวม) บน LCD
  lcd.setCursor(15, 3);  //กำหนดตำแหน่ง cursor บน LCD ที่แถวที่ 3, ตำแหน่งคอลัมน์ที่ 15
  lcd.print("Baht");     //แสดงข้อความ "Bath" บน LCD
}

//เริ่มฟังก์ชัน void lcdPrintProduct(int i)เพื่อแสดงข้อมูลของสินค้าที่ตำแหน่งที่ i ในรายการบน LCD
void lcdPrintProduct(int i) {

  String productName = list[i].name;  //กำหนดตัวแปรเก็บชื่อสินค้าให้เปลี่ยนเป็น string
  String Short_productName;           //กำหนดตัวแปรที่จะเก็บชื่อสินค้าที่กำหนดความยาวตัวอักษร

  Short_productName = productName.substring(0, 19);  //กำหนดความยาวตัวอักษรกับหน้าจอ

  lcd.clear();  //คำสั่งเพื่อลบข้อมูลทั้งหมดที่แสดงบน LCD

  if (list_current == 0) return;  //ใช้เพื่อตรวจสอบว่าถ้าไม่มีสินค้าในรายการ (list_current == 0) ให้ทำการออกจากฟังก์ชันทันที

  lcd.setCursor(0, 0);           // กำหนดตำแหน่ง cursor บน LCD ที่แถวที่ 0, ตำแหน่งคอลัมน์ที่ 0
  lcd.print(Short_productName);  // แสดงชื่อของสินค้าที่ตำแหน่ง i ในรายการบน LCD
  lcd.setCursor(0, 1);           //กำหนดตำแหน่ง cursor บน LCD ที่แถวที่ 1, ตำแหน่งคอลัมน์ที่ 0
  lcd.print("Qt.");              //แสดงข้อความ "Qt." (ย่อมาจาก Quantity) บน LCD
  lcd.setCursor(3, 1);           // กำหนดตำแหน่ง cursor บน LCD ที่แถวที่ 1, ตำแหน่งคอลัมน์ที่ 3
  lcd.print(list[i].count);      //แสดงจำนวนของสินค้าที่ตำแหน่ง i ในรายการบน LCD
  lcd.setCursor(11, 1);          //กำหนดตำแหน่ง cursor บน LCD ที่แถวที่ 1, ตำแหน่งคอลัมน์ที่ 11
  lcd.print(list[i].price);      //แสดงราคาของสินค้าที่ตำแหน่ง i ในรายการบน LCD
  lcd.setCursor(15, 1);          //กำหนดตำแหน่ง cursor บน LCD ที่แถวที่ 1, ตำแหน่งคอลัมน์ที่ 15
  lcd.print("Baht");             // แสดงข้อความ "Baht" บน LCD
}

//เริ่มฟังก์ชัน void loop()
void loop() {
  if (WiFi.status() == WL_CONNECTED) {                    //ทำงานเมื่อ Arduino มีการเชื่อมต่อกับเครือข่าย Wi-Fi (WiFi.status() == WL_CONNECTED)
    if (mySerial.available()) {                           //มีข้อมูลที่รอการรับผ่านทาง Serial (mySerial.available())
      String buf = mySerial.readString();                 //ใช้อ่านข้อมูลที่ถูกส่งมาทาง Serial จนกว่าจะขึ้นบรรทัดใหม่ \r (carriage return) ข้อมูลที่ถูกอ่านจะถูกเก็บไว้ในตัวแปร buf ซึ่งเป็นประเภท String
      String code = buf.substring(0, buf.indexOf("\r"));  //เป็นการดึงข้อมูลที่ถูกอ่านมาจาก buf โดยเริ่มต้นที่ตำแหน่งที่ 0 ถึงตำแหน่งที่พบ \r (carriage return)จะดึงข้อมูลตั้งแต่ตำแหน่งแรกของ buf จนถึงตำแหน่งก่อน \r และเก็บในตัวแปร code

      HTTPClient http;  //ใช้ในการทำ HTTP requests ใน ESP32 (หรือ Arduino ที่ใช้ ESP8266)

      //สร้าง URL ของเว็บเซิร์ฟเวอร์ (serverUrl) และทำ HTTP POST request ไปยัง URL
      String serverUrl = "your_ip/product_info.php?barcode=" + code + "&basket_code=1&product_amount=1";
      http.begin(client, serverUrl);                                            //ใช้เริ่มต้นการเชื่อมต่อกับเว็บเซิร์ฟเวอร์
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");      //ใช้เพิ่มหัว HTTP header ที่ระบุว่าข้อมูลที่จะส่งใน HTTP POST request เป็นแบบ application/x-www-form-urlencoded ซึ่งเป็นรูปแบบที่ใช้สำหรับส่งข้อมูลแบบ key-value ในรูปแบบของ HTML form
      String postData = "barcode=" + code + "&basket_code=1&product_amount=1";  //เป็น String ที่เก็บข้อมูลที่จะส่งใน HTTP POST requestข้อมูลถูกเตรียมเป็นรูปแบบ key-value โดยมี key คือ "barcode", "basket_code", และ "product_amount"
      int httpCode = http.POST(postData);                                       //คำสั่งนี้ทำการส่ง HTTP POST request ไปยังเว็บเซิร์ฟเวอร์ที่ได้ระบุ โดยใส่ข้อมูลที่ต้องการส่งในตัวแปร postData ผลลัพธ์ของการส่ง request จะถูกเก็บไว้ใน httpCode, โดยที่ httpCode จะบ่งบอกถึงสถานะของการทำงาน

      if (httpCode > 0) {                   //คำสั่งเช็คเงื่อนไขว่า HTTP request ทำงานสำเร็จ (httpCode > 0) หรือไม่
        String payload = http.getString();  //ใช้เพื่อดึงข้อมูล response ที่ได้รับจากเว็บเซิร์ฟเวอร์และเก็บไว้ในตัวแปร
        DynamicJsonDocument doc(1024);      // สร้างอ็อบเจกต์ของ DynamicJsonDocument ซึ่งเป็นอ็อบเจกต์ที่ใช้ในการแปลงข้อมูล JSON
        deserializeJson(doc, payload);      //ใช้เพื่อแปลงข้อมูล JSON จาก String (payload) เป็น DynamicJsonDocument

        String product_name = doc["product_name"].as<String>();  //ใช้เพื่อดึงค่าของ property "product_name" จาก JSON และเก็บไว้ในตัวแปร product_name
        int product_price = doc["price"].as<int>();              //ใช้เพื่อดึงค่าของ property "price" จาก JSON และเก็บไว้ในตัวแปร product_price

        for (int i = 0; i < list_current; i++) {  //ลูป for ใช้สำหรับการค้นหาว่าสินค้าที่มีรหัส code ที่ได้รับจากเว็บเซิร์ฟเวอร์ (ในตัวแปร code) มีในรายการ list หรือไม่
          if (list[i].code == code) {             //ถ้าพบว่ามีสินค้านี้อยู่ในรายการ, จะทำการเพิ่มจำนวนสินค้าที่มีในรายการ (ในตัวแปร list[i].count)
            list[i].count++;
            list_display = i;  //ตั้งค่าตัวแปร list_display เพื่อบอกว่าสินค้านี้ถูกแสดงบน LCD
            updated = true;    //ตั้งค่าตัวแปร updated เป็น true
            return;            //ทำการ return เพื่อออกจากลูป
          }
        }

        list[list_current].code = code;            //กำหนดรหัสสินค้าในตำแหน่ง list_current ของ list เท่ากับรหัสสินค้าที่ได้รับจากเว็บเซิร์ฟเวอร์ (code
        list[list_current].name = product_name;    //กำหนดชื่อสินค้าในตำแหน่ง list_current ของ list เท่ากับชื่อสินค้าที่ได้รับจากเว็บเซิร์ฟเวอร์ (product_name
        list[list_current].count = 1;              //กำหนดจำนวนสินค้าในตำแหน่ง list_current ของ list เท่ากับ 1 เนื่องจากเป็นการเพิ่มสินค้าใหม่
        list[list_current].price = product_price;  //กำหนดราคาสินค้าในตำแหน่ง list_current ของ list เท่ากับราคาสินค้าที่ได้รับจากเว็บเซิร์ฟเวอร์ (product_price
        list_display = list_current;               //กำหนด list_display เท่ากับ list_current เพื่อบอกว่าสินค้าที่ถูกแสดงบน LCD คือสินค้าที่ถูกเพิ่มเข้าไปใน list
        list_current++;                            // เพิ่มค่า list_current ทีละ 1 เพื่อระบุตำแหน่งของสินค้าใหม่ใน list
        updated = true;                            //ตั้งค่า updated เป็น true เพื่อบอกว่ามีการอัพเดตข้อมูลใน list
      }
      http.end();  //ใช้ปิดการเชื่อมต่อ HTTP
    }


    if (digitalRead(BTN_INCRE_PIN) == HIGH) {  //ใช้ตรวจสอบว่าปุ่มที่เชื่อมต่อกับขา BTN_INCRE_PIN ถูกกด (HIGH) หรือไม่
      list[list_display].count += 1;           //ถ้าปุ่มถูกกด, คำสั่งนี้ใช้เพิ่มจำนวนสินค้าที่ถูกแสดงบน LCD (ในตำแหน่ง list_display) ทีละ 1

      status = 1;  //ตั้งค่า status เป็น 1

      if (status == 1) {                                                                                                                                   //ถ้า status เป็น 1, ให้ทำงาน
        HTTPClient http;                                                                                                                                   //เพื่อให้สามารถทำ HTTP request ได้
        String serverUrl = "your_ip/insert.php?barcode=" + list[list_display].code + "&basket_code=1&product_amount=2";  //กำหนด URL ของเว็บเซิร์ฟเวอร์ที่ต้องการทำ HTTP POST request
        http.begin(client, serverUrl);                                                                                                                     //เริ่มต้นการเชื่อมต่อกับเว็บเซิร์ฟเวอร์
        http.addHeader("Content-Type", "application/x-www-form-urlencoded");                                                                               //เพิ่ม header HTTP ที่ระบุว่าข้อมูลที่จะส่งใน HTTP POST request เป็นแบบ application/x-www-form-urlencoded
        String postData = "barcode=" + list[list_display].code + "&basket_code=1&product_amount=2";                                                        //กำหนดข้อมูลที่จะส่งใน HTTP POST request ในรูปแบบ key-value
        int httpCode = http.POST(postData);                                                                                                                //ทำ HTTP POST request และรับค่า HTTP response code
        http.end();                                                                                                                                        //ปิดการเชื่อมต่อ HTTP หลังจากทำงานเสร็จสิ้น
      }

      updated = true;  //ตั้งค่า updated เป็น true เพื่อบอกว่ามีการอัพเดตข้อมูลใน list
    }

    if (digitalRead(BTN_DECRE_PIN) == HIGH) {  //ใช้ตรวจสอบว่าปุ่มที่เชื่อมต่อกับขา BTN_DECRE_PIN ถูกกด (HIGH) หรือไม่
      if (list[list_display].count > 0) {      //ตรวจสอบว่าจำนวนสินค้าที่ถูกแสดงบน LCD มีค่ามากกว่า 0 หรือไม่
        list[list_display].count -= 1;         //ถ้าปุ่มถูกกดและจำนวนสินค้าที่แสดงบน LCD มากกว่า 0, จะทำการลดจำนวนสินค้าทีละ 1

        status = -1;  //ตั้งค่า status เป็น -1

        if (status == -1) {                                                                                                                                  //ตรวจสอบว่า status เป็น -1 หรือไม่. ถ้าเป็น -1, ให้ทำงาน
          HTTPClient http;                                                                                                                                   //เพื่อทำ HTTP request
          String serverUrl = "your_ip/insert.php?barcode=" + list[list_display].code + "&basket_code=1&product_amount=1";  //กำหนด URL ของเว็บเซิร์ฟเวอร์ที่ต้องการทำ HTTP POST request
          http.begin(client, serverUrl);                                                                                                                     //เริ่มต้นการเชื่อมต่อกับเว็บเซิร์ฟเวอร์
          http.addHeader("Content-Type", "application/x-www-form-urlencoded");                                                                               //เพิ่ม header HTTP ที่ระบุว่าข้อมูลที่จะส่งใน HTTP POST request เป็นแบบ application/x-www-form-urlencoded
          String postData = "barcode=" + list[list_display].code + "&basket_code=1&product_amount=1";                                                        //กำหนดข้อมูลที่จะส่งใน HTTP POST request ในรูปแบบ key-value
          int httpCode = http.POST(postData);                                                                                                                //ทำ HTTP POST request และรับค่า HTTP response code
          http.end();                                                                                                                                        //ปิดการเชื่อมต่อ HTTP หลังจากทำงานเสร็จสิ้น
        }

        updated = true;                   //ตั้งค่า updated เป็น true เพื่อบอกว่ามีการอัพเดตข้อมูลใน list
      } else {                            //ถ้า status ไม่เป็น -1, ให้ทำงาน
        list_current = list_current - 1;  //ถ้าไม่มีการลดจำนวนสินค้า, ให้ลดค่า list_current ทีละ 1 เพื่อระบุตำแหน่งของสินค้าใหม่ใน list
        list_display = list_current;      //กำหนด list_display เท่ากับ list_current เพื่อให้แสดงสินค้าที่ถูกลดจำนวนบน LCD
      }
    }

    if (digitalRead(BTN_LEFT_PIN) == HIGH) {                                  //ใช้ตรวจสอบว่าปุ่มที่เชื่อมต่อกับขา BTN_LEFT_PIN ถูกกด (HIGH) หรือไม่
      list_display = list_display > 0 ? list_display - 1 : list_current - 1;  // conditional (ternary) operator ที่ใช้ในการกำหนดค่าของ list_display ถ้า list_display มากกว่า 0, ค่าใหม่ของ list_display จะเป็น list_display - 1 ถ้า list_display เป็น 0, ค่าใหม่ของ list_display จะเป็น list_current - 1
      updated = true;                                                         //ตั้งค่า updated เป็น true เพื่อบอกว่ามีการอัพเดตข้อมูลที่แสดงบน LCD
    }

    if (digitalRead(BTN_RIGHT_PIN) == HIGH) {                                 //ใช้ตรวจสอบว่าปุ่มที่เชื่อมต่อกับขา BTN_RIGHT_PIN ถูกกด (HIGH) หรือไม่
      list_display = list_display < list_current - 1 ? list_display + 1 : 0;  //คือ conditional (ternary) operator ที่ใช้ในการกำหนดค่าของ list_display ถ้า list_display น้อยกว่า list_current - 1, ค่าใหม่ของ list_display จะเป็น list_display + 1 ถ้า list_display เท่ากับ list_current - 1, ค่าใหม่ของ list_display จะเป็น 0

      int nextPosition = (list_display + 1) % list_current;  //กำหนดค่าตำแหน่งถัดไปของ list_display

      while (nextPosition != list_display && list[nextPosition].count == 0) {  //วนลูปขณะที่ตำแหน่งถัดไปไม่เท่ากับ list_display และจำนวนสินค้าในตำแหน่งถัดไปเท่ากับ 0
        // ลบตำแหน่งที่มี count เป็น 0 ออก
        for (int i = nextPosition; i < list_current - 1; i++) {  //ในลูป for ลบตำแหน่งที่มีจำนวนสินค้าเป็น 0 ออกจาก list
          list[i] = list[i + 1];
        }
        list_current--;  //ลดค่า list_current ทีละ 1 เพื่อระบุตำแหน่งของสินค้าใหม่ใน list

        nextPosition = (nextPosition + 1) % list_current;  //กำหนดค่าตำแหน่งถัดไปของ list_display
      }

      if (nextPosition != list_display) {
        // พบตำแหน่งถัดไปที่มีจำนวนสินค้ามากกว่า 0
        list_display = nextPosition;
        updated = true;  //ตั้งค่า updated เป็น true เพื่อบอกว่ามีการอัพเดตข้อมูลที่แสดงบน LCD
      }
    }

    if (digitalRead(BTN_RESET_PIN) == HIGH) {                                   //ใช้ตรวจสอบว่าปุ่มที่เชื่อมต่อกับขา BTN_RESET_PIN ถูกกด (HIGH) หรือไม่
      if (buttonPressStartTime == 0) {                                          //ตรวจสอบว่าเวลาเริ่มต้นการกดปุ่ม (buttonPressStartTime) เป็น 0 หรือไม่
        buttonPressStartTime = millis();                                        // ถ้าเป็น 0, ให้กำหนดค่า buttonPressStartTime เป็นค่าปัจจุบันของเวลา (millis()) เพื่อเก็บเวลาที่เริ่มต้นการกดปุ่ม
      } else {                                                                  //ถ้า buttonPressStartTime ไม่เท่ากับ 0, ให้ทำงาน
        if (millis() - buttonPressStartTime >= BUTTON_CONFIRMATION_DURATION) {  //ตรวจสอบว่าผ่านไปเวลาหลังจากการกดปุ่ม (millis() - buttonPressStartTime) มากกว่าหรือเท่ากับค่า BUTTON_CONFIRMATION_DURATION ถ้าใช่, แสดงว่าปุ่มถูกกดค้างไว้เกินระยะเวลาที่กำหนด
          list_current = 0;                                                     //ลบรายการสินค้าทั้งหมดโดยกำหนดค่า list_current เป็น 0
          list_display = 0;                                                     //กำหนดค่า list_display เป็น 0 เพื่อให้แสดงสินค้าที่ตำแหน่งที่ 0 บน LCD
          for (int i = 0; i < 1000; i++) {                                      //วนลูปเพื่อลบข้อมูลของแต่ละรายการสินค้าใน list โดยกำหนดค่าให้เป็นค่าเริ่มต้น
            list[i].code = "";
            list[i].name = "";
            list[i].price = 0;
            list[i].count = 0;
          }

          updated = true;  //ตั้งค่า updated เป็น true เพื่อบอกว่ามีการอัพเดตข้อมูล

          lcd.clear();                  //ลบข้อมูลที่แสดงบน LCD
          lcd.setCursor(0, 0);          //กำหนดตำแหน่งเริ่มต้นที่จะแสดงข้อความบน LCD
          lcd.print("Reset Complete");  //แสดงข้อความ "Reset Complete" บน LCD
          delay(5000);                  //หน่วงเวลา 5000 มิลลิวินาที (5 วินาที) เพื่อให้ข้อความ "Reset Complete" แสดงบน LCD ได้ค้างไว้
          lcd.clear();
          lcd.setCursor(0, 0);
          lcd.print("READY TO SCAN!");
        }
      }

      if (status == 0) {                                                                                                     //คำสั่งที่ใช้ตรวจสอบว่าค่าของตัวแปร status เท่ากับ 0 หรือไม่  (ค่าของ status เท่ากับ 0) ก็จะทำงาน
        HTTPClient http;                                                                                                     // เพื่อทำ HTTP request
        String serverUrl = "your_ip/insert.php?barcode=0&basket_code=1&product_amount=0";  //กำหนด URL ของเว็บเซิร์ฟเวอร์ที่ต้องการทำ HTTP POST request
        http.begin(client, serverUrl);                                                                                       //เริ่มต้นการเชื่อมต่อกับเว็บเซิร์ฟเวอร์
        http.addHeader("Content-Type", "application/x-www-form-urlencoded");                                                 //เพิ่ม header HTTP ที่ระบุว่าข้อมูลที่จะส่งใน HTTP POST request เป็นแบบ application/x-www-form-urlencoded
        String postData = "barcode=" + list[list_display].code + "&basket_code=1&product_amount=0";                          //กำหนดข้อมูลที่จะส่งใน HTTP POST request ในรูปแบบ key-value
        int httpCode = http.POST(postData);                                                                                  //ทำ HTTP POST request และรับค่า HTTP response code
        http.end();                                                                                                          //ปิดการเชื่อมต่อ HTTP หลังจากทำงานเสร็จสิ้น
      }

    } else {
      buttonPressStartTime = 0;  // รีเซ็ตเวลาเมื่อปล่อยปุ่ม
    }

    if (updated) {                    //ตรวจสอบว่ามีการอัพเดตข้อมูลหรือไม่
      lcdPrintProduct(list_display);  //ถ้ามีการอัพเดต, จะเรียกใช้ฟังก์ชั่น lcdPrintProduct เพื่อแสดงข้อมูลสินค้าที่ตำแหน่ง list_display บน LCD
      lcdPrintTotal();                //เรียกใช้ฟังก์ชั่น lcdPrintTotal เพื่อแสดงยอดรวมทั้งหมดของสินค้าบน LCD
      updated = false;                //ตั้งค่า updated เป็น false เพื่อบอกว่าข้อมูลได้ถูกแสดงแล้ว
    }
    delay(100);  //ทำการหน่วงเวลา 100 มิลลิวินาที (0.1 วินาที)
  }
}
