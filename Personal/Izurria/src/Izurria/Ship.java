package Izurria;


import java.awt.*;

import javax.swing.ImageIcon;

import java.awt.event.*;
import java.nio.file.FileSystems;
import java.nio.file.Path;
/**
 * A class that represents the ship object of the player
 * it can move and is controlled
 * small ship skin used to be for lives, no longer needed but kept in case of future need
 */
public class Ship extends ControlledObject {
 Path path = FileSystems.getDefault().getPath("").toAbsolutePath();
 ImageIcon ship = new ImageIcon(path + "/src/Izurria/" + "shipPlayer.png");
 ImageIcon lifeCounterShip = new ImageIcon(path + "/src/Izurria/" + "shipSkinSmall.gif");
/**
 * Constructor is literally the same as the ControlledObject constructor
 * @param x is the X position of the object
 * @param y is the y position of the object
 * @param color is the color of the object
 * @param control is the controller object that is assigned to the ship object
 */
   public Ship(int x, int y, Color color, Controller control) {
         super(x, y, color, control);
     }

/**
 * override
 * moves the ship depending on the objects assigned controller input
 * moves in intervals of 5 as 3 is too low and 7+ is inconsistent in paint
 * if the ship moves out of the game area (which is 800 x 800) it will move to the opposite boundry so if it moves past the right boundry it will appear to the left
 */
 public void move() {
              if (control.getKeyStatus(KeyEvent.VK_A)) {
            x -= 7;
        }
                if (control.getKeyStatus(KeyEvent.VK_D)) {
            x += 7;
        }
              if (control.getKeyStatus(KeyEvent.VK_W)) {
            y -= 7;
        }
                if (control.getKeyStatus(KeyEvent.VK_S)) {
            y += 7;
        }
        if (x > 800) {
            x = -50;
        }
        if (x < -50) {
            x = 800;
        }
        if (y > 800) {
            y = -50;
        }
        if (y < -50) {
            y= 800;
        }
 }

/**
 * override 
 * draws the ship
 * @param g the graphics instance that paints the object
 */
 public void draw(Graphics g) {
  // TODO Auto-generated method stub
  ship.paintIcon(null, g, this.getX(), this.getY());
 }

 /**
 * Draw ships for life counter
 * abandoned in favour of a string based HUD
 * obsolete and useless but kept just in case
 * @param g the graphics instance that paints the object
 */
    public void lifeDraw(Graphics g) {
        lifeCounterShip.paintIcon(null, g, this.getX(), this.getY());
    }

/**
 *override
 * returns the hit box of the ship using its coordinates and its dimensions, ship will ALWAYS be 50 x 50
 * @return the hitbox of the object, aka its area of existance
 */
 public Rectangle getBounds() {
  Rectangle shipHitbox = new Rectangle(this.getX(), this.getY(), 50, 50);//sprite will ALWAYS be 50 x 50
        return shipHitbox;
 }

}
