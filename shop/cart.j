const cart_api = "../api/main_api.php";

function update_cart_p(product_id, user_id){
    let data = {
        "product_id": product_id,
        "tedad": "1",
        "user_id" : user_id,
        "request" : "update_cart_p"
    };
    fetch(cart_api, {
        method: "POST",
        headers: {
            'Content-type': 'application/json',
        },
        body: JSON.stringify(data)
    }).then(response => {
        if (!response.ok){
            alert('مشکلی پیش آمد')
        }

        return response.json()
    }).then(data => {
        if (data['res_code'] == 200){
            const tedad = document.getElementById('tedad' + product_id);
            const price = document.getElementById('price' + product_id);
            tedad.innerText = data['tedad'];

            var price_data = data['price'];
            price_data = separate(price_data);

            price.innerText = price_data + ' T';
        }
        else if (data['res_code'] === 2)
            alert('محصول مورد نظر پیدا نشد!')
        else if (data['res_code'] === 3)
            alert('سفارش مورد نظر پیدا نشد!')
        else if (data['res_code'] === 4)
            alert('بیشتر از تعداد محصول نمی توان اضافه کرد')

    }).catch((error) => {
        console.log('Error : ' + error)
    })
}

function update_cart_m(product_id, user_id){
    const tedad = document.getElementById('tedad' + product_id);
    const price = document.getElementById('price' + product_id);
    let data = {
        "product_id": product_id,
        "tedad": "1",
        "user_id" : user_id,
        "request" : "update_cart_m"
    };
    fetch(cart_api, {
        method: "POST",
        headers: {
            'Content-type': 'application/json',
        },
        body: JSON.stringify(data)
    }).then(response => {
        if (!response.ok){
            alert('مشکلی پیش آمد')
        }

        return response.json()
    }).then(data => {
        if (data['res_code'] == 200){

            tedad.innerText = data['tedad'];

            var price_data = data['price'];
            price_data = separate(price_data);

            price.innerText = price_data + ' T';
        }
        else if (data['res_code'] === 2)
            alert('محصول مورد نظر پیدا نشد!')
        else if (data['res_code'] === 3)
            alert('سفارش مورد نظر پیدا نشد!')
        else if (data['res_code'] === 4)
            alert('حداقل تعداد در سبد خرید انتخاب شده')

    }).catch((error) => {
        console.log('Error : ' + error)
    })
}

function separate(Number)
{
    Number+= '';
    Number= Number.replace('/', '');
    x = Number.split('.');
    y = x[0];
    z= x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(y))
        y= y.replace(rgx, '$1' + '/' + '$2');
    return y+ z;
}