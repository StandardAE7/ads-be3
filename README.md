Berikut adalah repositori dan laporan TestCases Backend BE3 oleh Banabil Fawazaim Muhammad

## TASK:

Buatlah Database & API untuk Toko Online dengan fungsi:

1. Seller bisa CRUD Produk
2. Register/Login User (buyer)
3. Menampilkan katalog/produk
4. User menambahkan produk ke keranjang
5. User Checkout

## Penyelesaian

### Databases

Disini saya menggunakan 3 tabel, antara lain:

1. users
    
    tabel ini akan mendata:
    
    - user_id
    - username
    - password(enskripsi Hash)
2. products
    
    tabel ini akan mendata:
    
    - product_id
    - product_name
    - price
    - stock
    - description
    - seller_id (berdasarkan user_id dari tabel users)
3. carts
    
    tabel ini akan mendata:
    
    - carts_id
    - user_id(berdasarkan user_id dari tabel users)
    - product_id(berdasarkan product_id dari tabel products)
    - quantity

### Routes

1. Seller bisa CRUD Produk

Disini saya menggunakan routes `/products` dalam memanggil CRUD produk dari tiap seller, dengan masing-masing metode:

- GET
    
    Disini Seller bisa mendapatkan informasi barang mereka (READ)
    
    screenshot GET `/products`
    
    ![Untitled](img-report/GETproducts.png)
    
- POST
    
    Disini Seller bisa menambahkan produk mereka ke toko online ini (CREATE)
    
    body:
    
    ```
    {
        "product_name": "Product",
        "description": "Description",
        "price": 1.23,
        "stock": 45
    }
    ```
    
    screenshot POST `/products`
    
    ![Untitled](img-report/POSTproducts.png)
    
- PUT
    
    Disini Seller bisa mengubah informasi produk mereka dengan menambahkan `/{product_id}`  (UPDATE). Dan setiap Seller hanya bisa mengubah produk mereka sendiri (tidak bisa mengubah produk Seller lain)
    
    body:
    
    ```json
    {
        "product_name": "Product",
        "description": "Description",
        "price": 1.23,
        "stock": 45
    }
    ```
    
    screenshot PUT `/products/{id}`
    
    ![Untitled](img-report/PUTproducts.png)
    
    Catatan: body tidak perlu lengkap
    
- DELETE
    
    Disini Seller bisa menghapus produk mereka dari toko online dengan menambahkan `/{product_id}` (DELETE). Dan setiap Seller hanya bisa menghapus produk mereka sendiri (tidak bisa menghapus produk Seller lain)
    
    screenshot DELETE `/products/{id}`
    
    ![Untitled](img-report/DELETEproducts.png)
    
1. Register/Login User (buyer)

Buyer bisa melakukan register dan login dengan menggunakan routes `/register` dan `/login` dan ditambahkan dengan body berbentuk json seperti di bawah ini

```json
{
    "username":"username",
    "password":"password"
}
```

Jika berhasil melakukan register/login, user akan menerima token yang akan bisa dipakai saat CRUD produk (Seller), menambahkan produk ke keranjang dan checkout

screenshot untuk `/register`

    ![Untitled](img-report/POSTregister.png)

screenshot untuk `/login`

    ![Untitled](img-report/POSTlogin.png)

1. Menampilkan katalog/produk

Setiap pengguna API yang mempunyai maupun tidak mempunyai akun di toko online, mereka bisa melihat listing produk yang ada di toko online menggunakan routes `/catalog` dengan metode GET.

screenshot untuk `/catalog`

    ![Untitled](img-report/GETcatalog.png)

1. User menambahkan produk ke keranjang

Setiap buyer akan memiliki cart dimana setiap barang yang masuk ke keranjang akan didata ke database cart dengan diassign jumlah yang ingin dimasukan ke keranjang dan tak lupa id buyer juga didata ke database. Disini menggunakan routes `/cart` dengan metode:

- POST
    
    Buyer akan menambahkan barang ke cart 
    
    screenshot POST `/cart` 
    
    ![Untitled](img-report/POSTcart.png)
    
- GET
    
    Buyer dapat memeriksa semua barang yang berada di cart mereka
    
    screenshot GET `/cart`
    
    ![Untitled](img-report/GETcart.png)
    
1. User Checkout

Setelah barang dimasukan ke cart, buyer dapat melakukan checkout semua barang di cart dengan routes `/checkout` metode POST. Jika salah satu produk kekurangan, maka checkout pada produk tersebut tidak dilakukan. Setelah berhasil melakukan checkout, buyer akan mendapatkan total pembayaran yang harus ia lakukan.

screenshot POST `/checkout`

    ![Untitled](img-report/POSTcheckout.png)