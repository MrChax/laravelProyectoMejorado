<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Agregado por tema de seguridad ajax-->

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

        
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            table, th , td {
                border : 1px solid #ff0000;
                border-collapse: collapse;
                
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div style="height:100vh; margin: 3rem; display: grid; place-items: center">
            <table>
                <tbody>
                    <tr>
                        <th>Avatar</th>
                        <th>Name</th>
                    </tr>
                </tbody>
                @foreach ($users as $user )
                    <tr>
                        <td><img src="{{$user->avatar_url}}" alt="foto" width="100px" height="100px"></td>
                        <td><p>{{$user->login}}</p></td>
                        <td><button class="btn btn-primary" onclick="redirect('{{$user->login}}')">Ver informacion</button></td>
                    </tr>                 
                @endforeach
            </table> 

            <!-- Modal -->
            <div class="modal fade" id="usersDetailsModal" tabindex="-1" aria-labelledby="usersDetailsModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="display: flex; justify-content: center; align-items: center">
                    <div class="modal-content" style="width: fit-content;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="usersDetailsModalLabel">User details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div id="usersDetailsModalBody" class="modal-body text-center">
                        
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Modal -->

            
        </div> 
        
        <script>
            function redirect(login){
                const body = {username: login };
                $.ajax({
                    method: 'POST',
                    url: '/usersDetails',
                    headers: {
                        'X-CSRF-TOKEN': document.getElementsByTagName("meta")[2].content, /*Agregado por tema seguridad ajax */
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify(body),       //el data es la informacion que le vamos a estar mandando por ajax a nuestro controller
                    
                    success: (data /*En este parametro va a venir la informacion que devuelve el controller o la api en este caso vuelve el return del json encode, le puedo poner el nombre que quiera ya que es la respuesta */) => {
                        $("#usersDetailsModalBody").empty(); //hace que no se acomulen los modales que voy abriendo
                        $("#usersDetailsModal").modal({show:true});
                        const parsedResponse = JSON.parse(data);  //paso lo que retorna el controller a JSON FORMAT

                        const avatarUrl = parsedResponse.avatar_url ?? 'does not especify';             //si la variable parseResponse es null, undefined o califica como falso se queda con lo que sigue despues del ?? ESTE OPERADOR SE LLAMA COALECSING OPERATOR
                        const createdAt = new Date(parsedResponse.created_at).toLocaleString("es-AR") ?? 'doest not especify'; //pasa por el constructor de Date en js y nos quedamos con el metodo toLocaleString y le pasamos el parametro "es-AR"
                        const company = parsedResponse.company ?? 'doest not especify';
                        const email = parsedResponse.email ?? 'doest not especify';
                        const followers = parsedResponse.followers ?? 'doest not especify';
                        const following = parsedResponse.following ?? 'doest not especify';
                        const location = parsedResponse.location ?? 'doest not especify';
                        const login = parsedResponse.login ?? 'doest not especify';
                        const name = parsedResponse.name ?? 'doest not especify';
                        const table =  `<table>
                                            <tbody>
                                                <tr>
                                                    <th>Avatar</th>
                                                    <th>Created At</th>
                                                    <th>Company</th>
                                                    <th>Email</th>
                                                    <th>Followers</th>
                                                    <th>Following</th>
                                                    <th>Location</th>
                                                    <th>UserName</th>
                                                    <th>Name</th>                                               
                                                </tr> 
                                                
                                                <tr>
                                                    <td><img src="${avatarUrl}" alt="avatar url" width="100" height="100"></td>
                                                    <td><p>${createdAt}</p></td>
                                                    <td><p>${company}</p></td>
                                                    <td><p>${email}</p></td>
                                                    <td><p>${followers}</p></td>
                                                    <td><p>${following}</p></td>
                                                    <td><p>${location}</p></td>
                                                    <td><p>${login}</p></td>
                                                    <td><p>${name}</p></td>                                               
                                                </tr>   
                                            
                                            
                                            </tbody>

                                        
                            
                                        </table>`
                        $('#usersDetailsModalBody').append(table);    //inserto lo que contiene la variable table en #usersDetailsModalBody                                   
                    },

                    error: (data) => {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Do you want to continue',
                            icon: 'error',
                            confirmButtonText: 'Cool'
                        })
                    }

                    
                })
               
                
            }

        </script>
 
    </body>
</html>
