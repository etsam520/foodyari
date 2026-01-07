<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    

<script>
    let url = '{{ $url }}';
    window.open(url, "popupWindow", "width=600,height=400,scrollbars=yes,resizable=yes");
    // const urlObj = new URL(url);

    // // Get the base URL without the query string
    // let baseUrl = urlObj.origin + urlObj.pathname;
    // // baseUrl = baseUrl.replace('?', ''); // This line is unnecessary

    // // Extract query parameters
    // const queryParams = new URLSearchParams(urlObj.search);

    // // Convert query parameters to an object or key-value pairs
    // let paramsObject = {};
    // queryParams.forEach((value, key) => {
    //     paramsObject[key] = value;
    // });

    // // Output results
    // // console.log("Base URL:", baseUrl);
    // // console.log("Query Parameters:", paramsObject);

    // const form = document.createElement('form');
    // form.method = 'post';
    // form.action = baseUrl; // Set the action attribute to the base URL

    // // Iterate over each query parameter and create input elements
    // Object.entries(paramsObject).forEach(([key, value]) => {
    //     const input = document.createElement('input');
    //     input.type = 'hidden'; // Use hidden inputs to avoid showing them to users
    //     input.name = key; // Set input name as the key
    //     input.value = value; // Set input value as the value

    //     form.appendChild(input);
    // });
    // const submit = document.createElement('button');
    // submit.type = "submit";
    // form.appendChild(submit);


    // // Append the form to the body (or any container)
    // // document.body.innerHTML = form;
    // document.body.appendChild(form);

    // Submit the form
    // form.submit();
    // console.log(form.submit());
    
</script>
</body>
</html>
