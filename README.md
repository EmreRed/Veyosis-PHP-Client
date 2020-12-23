# Veyosis (veyosis.com) PHP API Client  
veyosis.com API servisleri için PHP API kütüphanesi.

#### AUTH
```php
$veyosis = new Veyosis($token);
/* veya */
Veyosis::auth($token);
```
`$token`: Veyosis API Kodu *  

---

## MARKA İŞLEMLERİ  
#### Marka Listeleme  
```php
Veyosis::brand()->get();
```

---

## İZİN YÖNETİMİ  
#### Tekil İzin Ekleme  
```php
Veyosis::consent()->single($brand,$type,$recipientType,$recipient,$source,$consentDate,$status);
```
`$brand`: İzin eklemek istediğiniz marka kodu *  
`$type`: İzin tipi (ARAMA/MESAJ/EPOSTA) *  
`$recipientType`: Alıcı tipi (BIREYSEL/TACIR) *  
`$recipient`: Alıcı *  
`$source`: İzin kaynağı (HS_) *  
`$consentDate`: İzin tarihi *  
`$status`: İzin durumu (ONAY/RET) *  

---

#### Çoklu İzin Ekleme (Asenkron)
```php
Veyosis::consent()->async($brand,$recipients);
```
`$brand`: İzin eklemek istediğiniz marka kodu *  
`$recipients`:
```php
[
  [
    'recipientType' => '',
    'type' => '',
    'recipient' => '',
    'source' => '',
    'consentDate' => '',
    'status' => ''
  ],
  .
  .
  .
]
```

---

#### Çoklu İzin Ekleme Durumu
```php
Veyosis::consent()->status($transaction);
```
`$transaction`: Çoklu izin isteğinde dönen işlem kodu *  

---

## İZİN SORGULAMA  
#### Tekil İzin Sorgulama  
```php
Veyosis::report()->single($brand,$type,$recipientType,$recipient);
```
`$brand`: İzin eklemek istediğiniz marka kodu *  
`$type`: İzin Tipi (ARAMA/MESAJ/EPOSTA) *  
`$recipientType`: Alıcı Tipi (BIREYSEL/TACIR) *  
`$recipient`: Alıcı *  

---

#### Çoklu İzin Sorgulama (Asenkron)
```php
Veyosis::consent()->async($brand,$type,$recipientType,$recipients);
```
`$brand`: İzin eklemek istediğiniz marka kodu *  
`$type`: İzin Tipi (ARAMA/MESAJ/EPOSTA) *  
`$recipientType`: Alıcı Tipi (BIREYSEL/TACIR) *  
`$recipient`: Alıcılar (Array şeklinde) *  

---

#### Çoklu İzin Sorgulama Durumu
```php
Veyosis::report()->status($transaction);
```
`$transaction`: Çoklu izin isteğinde dönen işlem kodu *  

---

#### DİĞER PARAMETRELER  
```php
Veyosis::get()->error()->code; // Alınan son hatayı getirir.
Veyosis::get()->error()->desc; // Alınan son hata açıklamasını getirir.

Veyosis::get()->request()->body; // Yapılan son isteği getirir.
Veyosis::get()->request()->url; // Yapılan son istek url adresini getirir.

Veyosis::get()->result()->code; // Yapılan son istek cevabının http kodunu getirir.
Veyosis::get()->result()->body; // Yapılan son istek cevabını adresini getirir.
```
