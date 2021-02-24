package Izurria;


/*
 * @Author Miguel De Vera
 * @Version 2018-01-01
 */

import java.awt.*;

/**
 * Represents a bullet fired from a ship object.
 * A bullet can travel forward and hit other objects.
 */
public class Bullet extends MovingObject{

  /**
   * the diameter of the bullet, initially used for boundry however now a simple debug tool
   */
 private int diameter;
    
  /**
   * Initializes a bullet object
   * A bullet has coordinates, dimensions and a color.
   * @param xPos the x-coordinate of the bullet
   * @param yPos the y-coordinate of the bullet
   * @param yPos the diameter of the bullet, no longer important but just in case is kept
   * @param color the color of the bullet
   * @param diameter is a useless test parameter but initially used to give a diamater for the boundry
   */
    public Bullet(int xPos, int yPos, int diameter, Color color) {
        super(xPos, yPos, 0, 0, color);
        this.diameter = diameter;
    }
    
    /**
   * returns the bounds of a bullet
   * uses the bullet's x, y as the hit box's location and the bullet itself is 7 X 15
   * @return bulletHitBox is the hit box of the bullet object, aka its area of existence
   */
 public Rectangle getBounds(){
   Rectangle bulletHitbox = new Rectangle(getX(), getY(), 7, 15);//width of 7, height of 5
         return bulletHitbox;
 }
  /**
   * draws the bullet in a panel
   * just used in repaint()
   * @param g a Graphics object that will print the bullet
   */
    public void draw(Graphics g){
        g.setColor(color);//color from the bullet object
        g.fillRect(this.getX(), this.getY(), 7, 15);

    }
    /**
   * returns the diameter value of the bullet
   * Essentially useless
   * @return the diameter of said bullet, used only in initial testing,kept just in case
   */
 public int getDiameter() {
        return diameter;
    }

}
