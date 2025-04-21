import rooter from './controller.js'
import updateNavbar from  './navbar.js'


    rooter("home")
    updateNavbar()
    window.navigation = function (direction){
        rooter(direction)
    }
    



