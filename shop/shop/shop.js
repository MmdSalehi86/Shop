const cart_api = "../api/main_api.php";

function add_cart(product_id, user_id){
    if (user_id === -1)
        location.href = '../index.php?pr_id=' + product_id;
    let data = {
        "product_id": product_id,
        "tedad": "1",
        "user_id" : user_id,
        "request" : "add_cart"
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
        if (data['res_code'] === 2)
            alert('محصول مورد نظر پیدا نشد!')
        else if (data['res_code'] === 3)
            alert('سفارش مورد نظر پیدا نشد!')
        else if (data['res_code'] === 4)
            alert('بیشتر از تعداد محصول نمی توان اضافه کرد')

    }).catch((error) => {
        console.log(error)
    })
}

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
        if (data['res_code'] === 2)
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
    //if (tedad.innerText === '1')
    //return;
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
        if (data['res_code'] === 2)
            alert('محصول مورد نظر پیدا نشد!')
        else if (data['res_code'] === 3)
            alert('سفارش مورد نظر پیدا نشد!')

    }).catch((error) => {
        console.log('Error : ' + error)
    })
}