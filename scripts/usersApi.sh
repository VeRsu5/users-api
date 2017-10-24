#!/bin/bash
while
    echo -e "\n\n"
    echo -e "\033[1;33mWelcome to users REST API script!\033[m"
    echo "Select any one of the following options: "
    echo "1. Create a user."
    echo "2. Get all users."
    echo "3. View single user using email."
    echo "4. Delete a user."
    echo "5. Quit."
    echo -n "Enter option: "
    read option

    case $option in
        1)  
            echo -e "\n"
            echo -e "\033[1;32mInsert a user!\033[m"
            echo -n "Enter first name (only alphabets): "
            read FIRST_NAME
            echo -n "Enter last name (only alphabets): "
            read LAST_NAME
            echo -n "Enter email address: "
            read EMAIL
            echo -n "Enter password (6 digits): "
            read PASSWORD
            echo -n "Enter isAdmin (true or false): "
            read IS_ADMIN

            
            if [[ $FIRST_NAME = $(echo "$FIRST_NAME" | tr -dc '[A-za-z ]') && \
                    $LAST_NAME = $(echo "$LAST_NAME" | tr -dc '[A-za-z ]') && \
                    `echo $PASSWORD|wc -c` -ge 7 \
                ]] 
            then
                
                HEADER_CONTENT_TYPE="Content-Type: application/json"
                JSON="{ \"first_name\":\"$FIRST_NAME\", \"last_name\":\"$LAST_NAME\", \"email\":\"$EMAIL\", \"password\":\"$PASSWORD\", \"isAdmin\":$IS_ADMIN }"
                echo ""
                curl -d "$JSON" -H "${HEADER_CONTENT_TYPE}" -X POST http://localhost:80/users-api/public/index.php/api/user/post

            else
                echo -e "\033[1;31mPlease enter valid data!\033[m"
            fi

            ;;
        2)  
            echo -e "\n"
            echo -e "\033[1;32mFetching all users!\033[m"
            json=`curl -H "Accept: application/json" -H "Content-Type: application/json" -X GET http://localhost:80/users-api/public/index.php/api/users`

            length=`echo "$json" | jq '. | length'`

            i=0
            max=$length

            echo -n "|"
            printf "%-4s" "_id"
            echo -n "|"
            printf "%-19s" "     first_name"
            echo -n "|"
            printf "%-19s" "     last_name"
            echo -n "|"
            printf "%-29s" "           email"
            echo -n "|"
            printf "%-19s" "     password"
            echo -n "|"
            printf "%-4s" "isAdmin"
            echo -n "|"
            echo -e "\n"

            while [ $i -lt $max ]
            do
                printf "%5s" `echo "$json" | jq '.['$i']._id'`
                printf "%20s" `echo "$json" | jq '.['$i'].first_name'` `echo "$json" | jq '.['$i'].last_name'`
                printf "%30s" `echo "$json" | jq '.['$i'].email'`
                printf "%20s" `echo "$json" | jq '.['$i'].password'`
                printf "%5s" `echo "$json" | jq '.['$i'].isAdmin'`
                echo -e "\n"
                true $(( i=i+1 ))
            done
            ;;
        3)
            echo -e "\n"
            echo -e "\033[1;32mFetch single user!\033[m"
            echo -n "Enter email: "
            read EMAIL

            json=`curl -H "Accept: application/json" -H "Content-Type: application/json" -X GET http://localhost:80/users-api/public/index.php/api/euser/$EMAIL`

            check=`echo "$json" | jq '.[0]._id'`

            if [[ $check != 'null' ]]
            then

                echo -n "|"
                printf "%-4s" "_id"
                echo -n "|"
                printf "%-19s" "     first_name"
                echo -n "|"
                printf "%-19s" "     last_name"
                echo -n "|"
                printf "%-29s" "           email"
                echo -n "|"
                printf "%-19s" "     password"
                echo -n "|"
                printf "%-4s" "isAdmin"
                echo -n "|"
                echo -e ""

                printf "%5s" `echo "$json" | jq '.[0]._id'`
                printf "%20s" `echo "$json" | jq '.[0].first_name'` `echo "$json" | jq '.[0].last_name'`
                printf "%30s" `echo "$json" | jq '.[0].email'`
                printf "%20s" `echo "$json" | jq '.[0].password'`
                printf "%5s" `echo "$json" | jq '.[0].isAdmin'`
                echo -e ""
            
            else
                echo -e "\033[1;31mNo such user found!\033[m"
            fi
            ;;
        4)  
            echo -e "\n"
            echo -e "\033[1;31mDelete a user!\033[m"
            echo -n "Enter the id of the user: "
            read id
            echo ""
            curl -X "DELETE" http://localhost:80/users-api/public/index.php/api/user/delete/$id
            ;;
        5)
            echo -e "\033[1;31mGoodbye!\033[m"
            ;;
        *)
            echo -e "\033[1;31mPlease choose a valid option!\033[m"
            ;;
    esac

       [ "$option" -ne 5 ]            # test the limit of the loop.
do :;  done