## Console

* Open a new terminal window and CD to your projects directory
  ```bash
  cd ${PROJECT_PATH}/environment
  ```
  
* Get list of all available commands
  ```bash
  make help
  ```

* This command updates the environment or your Gemfile changes.
  ```bash
  make build
  ```

* This command runs all necessary containers in the environment. 
  ```bash
  make start
  ```

* This command "teleports" you to the command shell where you can execute project related commands.
  ```bash
  make shell
  ```
  
* This command will stop all containers related to this environment.
  ```bash
  make stop
  ```

After that your project will be able to visit by `http://${PROJECT_NAME}.localhost` in the GoogleChrome and Postman. By default, it's `http://weather_app.localhost`.
