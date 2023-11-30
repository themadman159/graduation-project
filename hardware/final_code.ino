#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <LiquidCrystal_I2C.h>
#include <SoftwareSerial.h>
#include <ArduinoJson.h>
#include <Dictionary.h>

unsigned long buttonPressStartTime = 0;
const unsigned long BUTTON_CONFIRMATION_DURATION = 3000;

#define BTN_INCRE_PIN D5
#define BTN_DECRE_PIN D6
#define BTN_LEFT_PIN D7
#define BTN_RIGHT_PIN D8
#define BTN_RESET_PIN D0

WiFiClient client;

const char* ssid = "your_name_ssid";
const char* password = "your_password_ssid";

LiquidCrystal_I2C lcd(0x27, 20, 4);
SoftwareSerial mySerial(3, 1);

int status = 0;

struct Product {
  String code;
  String name;
  int price;
  int count;
};

Product list[1000];
int list_current = 0;
int list_display = 0;

bool updated = false;
bool BTN_RESET_Pressed = false;

void setup() {
  lcd.begin();
  lcd.backlight();

  pinMode(BTN_INCRE_PIN, INPUT);
  pinMode(BTN_DECRE_PIN, INPUT);
  pinMode(BTN_LEFT_PIN, INPUT);
  pinMode(BTN_RIGHT_PIN, INPUT);
  pinMode(BTN_RESET_PIN, INPUT);

  mySerial.begin(9600);

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    lcd.setCursor(0, 0);
    lcd.print("Connecting to WiFi");
  }
  lcd.setCursor(0, 1);
  lcd.print("OK!");
}

void lcdPrintTotal() {
  int count = 0;
  int sum = 0;
  for (int i = 0; i < list_current; i++) {
    sum += list[i].price * list[i].count;
    count += list[i].count;
  };

  lcd.setCursor(0, 2);
  lcd.print("Total");
  lcd.setCursor(11, 3);
  lcd.print(sum);
  lcd.setCursor(0, 3);
  lcd.print("Qt.");
  lcd.setCursor(3, 3);
  lcd.print(count);
  lcd.setCursor(15, 3);
  lcd.print("Bath");
}

void lcdPrintProduct(int i) {
  lcd.clear();

  if (list_current == 0) return;

  lcd.setCursor(0, 0);
  lcd.print(list[i].name);
  lcd.setCursor(0, 1);
  lcd.print("Qt.");
  lcd.setCursor(3, 1);
  lcd.print(list[i].count);
  lcd.setCursor(11, 1);
  lcd.print(list[i].price);
  lcd.setCursor(15, 1);
  lcd.print("Bath");
  
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    if (mySerial.available()) {
      String buf = mySerial.readString();
      String code = buf.substring(0, buf.indexOf("\r"));

      HTTPClient http;
      String serverUrl = "your_IP_Address/product_info.php?barcode=" + code + "&basket_code=1&product_amount=1";
      http.begin(client, serverUrl);
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");
      String postData = "barcode=" + code + "&basket_code=1&product_amount=1";
      int httpCode = http.POST(postData);

      if (httpCode > 0) {
        String payload = http.getString();
        DynamicJsonDocument doc(1024);
        deserializeJson(doc, payload);

        String product_name = doc["product_name"].as<String>();
        int product_price = doc["price"].as<int>();

        for (int i = 0; i < list_current; i++) {
          if (list[i].code == code) {
            list[i].count++;
            list_display = i;
            updated = true;
            return;
          }
        }

        list[list_current].code = code;
        list[list_current].name = product_name;
        list[list_current].count = 1;
        list[list_current].price = product_price;
        list_display = list_current;
        list_current++;
        updated = true;
      }
      http.end();
    }


    if (digitalRead(BTN_INCRE_PIN) == HIGH) {
      list[list_display].count += 1;

      status = 1;

      if (status == 1) {
        HTTPClient http;
        String serverUrl = "your_IP_Address/insert.php?barcode=" + list[list_display].code + "&basket_code=1&product_amount=2";
        http.begin(client, serverUrl);
        http.addHeader("Content-Type", "application/x-www-form-urlencoded");
        String postData = "barcode=" + list[list_display].code + "&basket_code=1&product_amount=2";
        int httpCode = http.POST(postData);
        http.end();
      }

      updated = true;
      
    }

    if (digitalRead(BTN_DECRE_PIN) == HIGH) {
      if (list[list_display].count > 0) {
        list[list_display].count -= 1;

        status = -1;

        if (status == -1) {
          HTTPClient http;
          String serverUrl = "your_IP_Address/insert.php?barcode=" + list[list_display].code + "&basket_code=1&product_amount=1";
          http.begin(client, serverUrl);
          http.addHeader("Content-Type", "application/x-www-form-urlencoded");
          String postData = "barcode=" + list[list_display].code + "&basket_code=1&product_amount=1";
          int httpCode = http.POST(postData);
          http.end();
        }

        updated = true;
      } else {
        list_current = list_current - 1;
        list_display = list_current;
      }
      
    }

    if (digitalRead(BTN_LEFT_PIN) == HIGH) {
      list_display = list_display > 0 ? list_display - 1 : list_current - 1;
        updated = true;
      }

    if (digitalRead(BTN_RIGHT_PIN) == HIGH) {
      list_display = list_display < list_current - 1 ? list_display + 1 : 0;

      int nextPosition = (list_display + 1) % list_current;

      while (nextPosition != list_display && list[nextPosition].count == 0) {
        // ลบตำแหน่งที่มี count เป็น 0 ออก
        for (int i = nextPosition; i < list_current - 1; i++) {
          list[i] = list[i + 1];
        }
        list_current--;

        nextPosition = (nextPosition + 1) % list_current;
      }

      if (nextPosition != list_display) {
        // พบตำแหน่งถัดไปที่มีจำนวนสินค้ามากกว่า 0
        list_display = nextPosition;
        updated = true;
      }
      
    }

    if (digitalRead(BTN_RESET_PIN) == HIGH) {
      if (buttonPressStartTime == 0) {
        buttonPressStartTime = millis();  // เก็บเวลาเริ่มต้นการกดปุ่ม
      } else {
        // ถ้าปุ่มถูกกดค้างไว้เกิน BUTTON_CONFIRMATION_DURATION ให้รีเซ็ตค่า
        if (millis() - buttonPressStartTime >= BUTTON_CONFIRMATION_DURATION) {
          list_current = 0;
          list_display = 0;
          for (int i = 0; i < 1000; i++) {
            list[i].code = "";
            list[i].name = "";
            list[i].price = 0;
            list[i].count = 0;
          }

          updated = true;

          lcd.clear();
          lcd.setCursor(0, 0);
          lcd.print("Reset Complete");
          delay(5000);
        }
      }

      if (status == 0) {
        HTTPClient http;
        String serverUrl = "your_IP_Address/insert.php?barcode=0&basket_code=1&product_amount=0";
        http.begin(client, serverUrl);
        http.addHeader("Content-Type", "application/x-www-form-urlencoded");
        String postData = "barcode=" + list[list_display].code + "&basket_code=1&product_amount=0";
        int httpCode = http.POST(postData);
        http.end();
      }

    } else {
      buttonPressStartTime = 0;  // รีเซ็ตเวลาเมื่อปล่อยปุ่ม
    }

    if (updated) {
      lcdPrintProduct(list_display);
      lcdPrintTotal();
      updated = false;
    }
    delay(100);
  }
}
