aCode Challenge: Machine Translation Microservice
=================================================
This a technical challenge for Signa, by Jorge Serrano <jorsermar@gmail.com>

# Manual
In this section I´ll cover all the steps needed to run the translation microservice defined in the exercise. For sake of simplicty I'm asumming we´re
running on a Unix OS. This has been only been tested on macOS Monterey and CentOS 7.

## 1. Install && Deployment
```
git clone git@github.com:jorsermar/signa_babel.git
cd signa_babel
bash make.sh
docker-compose up --build
```

## 2. Usage
### API
- Translate 
> http://localhost:8080/translate/{langugage_code_to}/{urlencoded_string} 
>> http://localhost:8080/translate/en/Hola+mundo

- List requests
> http://localhost:8080/list

- Search for specific request
> http://localhost:8080/get/{langugage_code_to}/{urlencoded_string} 
>> http://localhost:8080/get/en/Hola+mundo

### CLI
We use ``` docker exec -it signa_babel-php_1 {command}``` to run the cli commands within the php container (signa_babel-php_1 by default). The commands we can use are:

- Translate 
> php bin/console app:translate {langugage_code_to} {string} 
>> php bin/console app:translate en "Hola mundo"

- List requests
> php bin/console app:list

- Search for specific request
> php bin/console app:get {langugage_code_to} {string} 
>> php bin/console app:get en "Hola mundo"


## 3. Scaling UP
We could easily scale the up using docker-compose scale property and traefik to balance the load, or something more complex if we were to deploy this in AWS

## 4. Testing
Due lack of time there are no tests written for the application

# Technical Justification
In this section I´ll try to explain all the technical decissions made (meaning decissions that are not covered by the requisites of the exercise).
### 1. Initial Skeleton. 
Instead of using a common template I´ve opted for starting from a blank slate. I want the project to look as clean as I can. Considering I´ve been asked to build my own service clients for the translation services, I´ve assumed than the less 3rd parties/unused code I deliver with this test, the better. And a simpler template we´ll make this challenge easier to correct too, I think.


### 2. Stack Tools && Versions


| Software  | Version | Reasoning |
| ------------- |:-------------:||
| nginx      | 1.23     | My webserver of choice, no strong feelings, just that I like it. 1.23 as the latest stable version available. |
| php      | 8.0     | PHP 8.1 is the recommended version by symfony doc. |
| mysql      | 5.7     | MySQL is the storage system I´m more confortable with. I´ve briefly considered MongoDB - it´s probably a better match for this kind of project - but given the limited time I can invest in the challenge I´ve taken the safest route. Following the same line of thinking, I´ve experienced some issues with 8.0, so I´ve opted for the older, more reliable version. |
| redis      | 7.0     | My prefered queueing system

### 3. Scheduler
I´ve set up a php container for the scheduling after running out of time for a better solution. THis could have been done if I´ve used Platform.sh from the beginning for designing the solution from scratch. In AWS this could have been done with Cloudwatch Events && lambda functions. The one included is not an elegant approach but it works and allows to use the rest of the service.

### 4. Other
I hope to be able to explain any other concern during the technical interview.


