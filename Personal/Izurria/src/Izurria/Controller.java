package Izurria;


//originally created by @Author Spartan Tech
import java.awt.event.KeyEvent;
import java.awt.event.KeyListener;

/**
 * Is an object that takes key presses and turns it into signals to later be turned into action
 */
public class Controller implements KeyListener {
  private boolean[] keyStatus;//this array will hold key status for all keycodes
  
/**
 * The controller constructor
 * creates an array of 256 booleans, 256 being around the max of the java key codes, highest is 222 but why not 256 amirite?
 */
  public Controller(){
         keyStatus = new boolean[256]; //the ones that matter are:
         //left:37 and right:39, up and down are 38 and 40 if needed
         //spacebar is 32
         //constants are KeyEvent.VK_LEFT, need to import java.awt.event.KeyEvent
     }
/**
 * Checks to see if the key is being pressed
 * @return false if the key is not being pressed, if it is being pressed it returns a true from that array index
 * @param keyCode is the keycode of a keypress, such as the int value from VK_S
 */
  public boolean getKeyStatus(int keyCode){
         if(keyCode < 0 || keyCode > 255)
             return false; 
         else
             return keyStatus[keyCode]; 
     }
 /**
  * The keypressed overide from keylistener
  * dictates the result of a key being pressed, in this case it makes the index of the array true meaning the keycode is pressed
  * @param e means something happened on the keyboard
  */
 public void keyPressed(KeyEvent e){
  keyStatus[e.getKeyCode()] = true; //this means this button is pressed
  //i.e key code of UP is currently pressed, will allow objects to 
  //use boolean for movement
 }

/**
 * The keypreleased overide from keylistener
 * dictates the result of a key being released, in this case it makes the index of the array meaning meaning the keycode is NOT pressed
 * @param e means something happened on the keyboard
 */
 public void keyReleased(KeyEvent e) {
  keyStatus[e.getKeyCode()] = false;
 }

/**
 * The keytyped overide from keylistener
 * not used here as we are not interested in typing, only pressing
 * @param arg0 is a useless placeholder argument, it means nothing
 */
 public void keyTyped(KeyEvent arg0) {

 }
/**
 * resets all the booleans in the array
 * used to reset the controller button states
 */
 public void resetController() {
  keyStatus = new boolean[256]; 
 }

}
