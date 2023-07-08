<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form  id="submitForm">
        @csrf
        <input type="text" placeholder="username" name="username" required>
        <input type="password" placeholder="password" name="password" required>
        <input type="text" placeholder="name" name="name" required>
        <input type="number" placeholder="point" name="point" required>
        <button type="submit">submit</button>
    </form>
    <hr>
    <style>
        table, th, td {
            border: 1px solid black;
            }
    </style>
    <table id="userTable">
        <thead>
            <tr>
                <th>username</th>
                <th>password</th>
                <th>name</th>
                <th>point</th>
                <th>date/time</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="tbody" border>
            @foreach ($users as $user)
            <tr id="user_{{$user->id}}">
                <th>{{$user->username}}</th>
                <th>{{$user->password}}</th>
                <th>{{$user->name}}</th>
                <th>
                    <button type="button" onclick="actionPoint( {{$user->id}}, 'r')">-</button>
                    <span id="user_point_{{$user->id}}">{{$user->point}}</span>
                    <button type="button" onclick="actionPoint( {{$user->id}}, 'a')">+</button>
                </th>
                <th>{{$user->created_at}}</th>
                <th>
                    <button type="button" onclick="deleteUser({{$user->id}})">Delete</button>
                </th>
            </tr>
            @endforeach
        </tbody>
    </table>
    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>

<script>
    $("#submitForm").on('submit' , function(e){
        e.preventDefault()
        const FormData = $(this).serialize()
        $.post(`{{url('user')}}` , FormData , function(data){
            if(data.success){
                document.getElementById('submitForm').reset();
                const html = `<tr id="user_`+data.user.id+`">
                                <th>`+data.user.username+`</th>
                                <th>`+data.password+`</th>
                                <th>`+data.user.name+`</th>
                                <th><button type="button" onclick="actionPoint( `+data.user.id+`, 'r')">-</button>
                                    `+data.user.point+`
                                    <button type="button" onclick="actionPoint( `+data.user.id+`, 'a')">+</button>
                                </th>
                                <th> `+data.user.created_at+`</th>
                                <th>
                                    <button type="button" onclick="deleteUser( `+data.user.id+`)">Delete</button>
                                </th>
                            </tr>`
                $("#tbody").append(html)
            }else{
                alert(data.msg)
            }
        })
    })

    function actionPoint(user_id , type){
        let current_point = $("#user_point_"+user_id).text()
        current_point = parseInt(current_point)
        if(type == 'r'){
            if(current_point > 0){
                current_point = current_point -= 1
            }
        }else{
            current_point = current_point += 1
        }
        $.post(`{{url('update-point')}}` , {user_id:user_id , point: current_point, _token:`{{csrf_token()}}`}  , function(data){
            if(data.success){
                $("#user_point_"+user_id).text(current_point)
            }else{
                alert(data.msg)
            }
        })
    }

    function deleteUser(user_id){
        if(confirm('Sure ??')){
            let user_el =  $("#user_"+user_id)
            $.post(`{{url('delete-user')}}` , {user_id:user_id , _token:`{{csrf_token()}}`} , function(data){
                if(data.success){
                    user_el.remove()
                }else{
                    alert(data.msg)
                }
            })      
        }
    }

    $(document).ready(function(){
        fetchAPI()
    })

    function fetchAPI(){
        $.post(`{{url('get-api')}}` , {_token:`{{csrf_token()}}`} , function(data){
          
        })
        // $.post('https://ssm-th.com/api/v2' , {key:"ab2c31fa20bb20ecc5260c05a3b1d305"} , function(data){
        //     console.log(data)
        // })
    }

</script>
</body>
</html>