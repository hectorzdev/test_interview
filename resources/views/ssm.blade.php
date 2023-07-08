<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    
    <style>
        table, th, td {
            border: 1px solid black;
            }
    </style>
    <table id="userTable">
        <thead>
            <tr>
                <th>service_id</th>
                <th>name</th>
                <th>rate</th>
                <th>min</th>
                <th>max</th>
            </tr>
        </thead>
        <tbody id="tbody" border>
            @foreach ($services as $service)
            <tr>
                <th>{{$service->service}}</th>
                <th>{{$service->name}}</th>
                <th>{{$service->rate}}</th>
                <th>{{$service->min}}</th>
                <th>{{$service->max}}</th>
            </tr>
            @endforeach
        </tbody>
    </table>
    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>

<script>
    
    // $(document).ready(function(){
    //     fetchAPI()
    // })

    // function fetchAPI(){
    //     $.post(`{{url('get-api')}}` , {_token:`{{csrf_token()}}`} , function(data){
          
    //     })
    //     // $.post('https://ssm-th.com/api/v2' , {key:"ab2c31fa20bb20ecc5260c05a3b1d305"} , function(data){
    //     //     console.log(data)
    //     // })
    // }

</script>
</body>
</html>