# API Documentation

## The api returns 200 status code and uses json to return an actual status code (e.g {"code" => 200}, {"code" => 400})

### POST /login.php
- #### requires 'username' and 'password' params (e.g. username=username;password=password)
- #### *returns* mixed response
### POST /signup.php
- #### requires 'fullname', 'username', 'password', 'user_id', and 'role' params
- #### *returns* mixed response


### GET /uniforms.php?action=\<action\>
- #### *\<action\>*
    - ##### index : returns all available shirts
    - ##### check : returns if a specific department shirt has an available stock, requires 'department' parameter (e.g /uniforms.php?action=check&department=dep)
### PUT /uniforms.php
- #### requires 'user_id' and 'shirt_name', 'shirt_name' as the name of the shirt
- #### *returns* mixed response
