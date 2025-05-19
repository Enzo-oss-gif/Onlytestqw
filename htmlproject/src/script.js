function onloadFunction() {
 if (window.smartCaptcha) {
 const container = document.getElementById('captchaContainer');
 const widgetId = window.smartCaptcha.render(container, {
 sitekey: 'ysc1_ST7eEgUw1JgS4s7w1w0LXkACSqU8oLZJQfqtvThadf846b17',
 hl: 'ru',
 robustness: 'normal' 
 });
 }
 }