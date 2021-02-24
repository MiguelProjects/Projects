package Izurria;


/**
 * @Author Miguel De Vera
 * @Version 2018-01-01
 */

import java.awt.*;
/**
 * Interface that houses the draw method used later on
 * is an interface as it is essentially a method that will vary from class to class but is still needed in many arguments and stuff
 */
public interface Drawable {
  
/**
 * the draw method
 * is blank as it is meant to be ovveriden, it draws an image depending on the object
 * @param g is the graphics object that will paint
 */
    public void draw(Graphics g);
}
