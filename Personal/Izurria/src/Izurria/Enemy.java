package Izurria;


/**
 * @Author Miguel De Vera
 * @Version 2018-01-01
 */

import java.awt.*;
import java.nio.file.FileSystems;
import java.nio.file.Path;

import javax.swing.ImageIcon;
/**
 * An enemy object that can move and serves as the main opponent in Izurria
 * needs to move so extends the movingObject class
 * has width, hight, coordinates and a type
 * the type dictates the image printed
 * originally had speed as well however that is no longer needed due to GamePanel
 */
public class Enemy extends MovingObject {
  private int enemytype, width, height;
  Path path = FileSystems.getDefault().getPath("").toAbsolutePath();
  ImageIcon alien1 = new ImageIcon(path + "/src/Izurria/" + "rightSprite.png");
  ImageIcon alien2 = new ImageIcon(path + "/src/Izurria/" + "leftSprite.png");
  ImageIcon alien3 = new ImageIcon(path + "/src/Izurria/" + "straightSprite.png");
  ImageIcon alien4 = new ImageIcon(path + "/src/Izurria/" + "traitorSprite.png");
  ImageIcon boss = new ImageIcon(path + "/src/Izurria/" + "bossSprite.png");
  
  /**
   * Enemy construcor
   * the boolean beamShot is used for beams
   * @param xPos is the horizontal location
   * @param yPos is the vertical location
   * @param xSpeed is the value that the object will move by in the horizontal direction
   * @param ySpeed is the value that the object will move by in the vertical direction
   * @param enemyType dictates the image icon associated with that enemy, will be printed later on in panel
   * @param color is the color of the object
   * @param width is the width of the enemy, used in hit box
   * @param height is the height of the enemy, used in hit box
   */
  public Enemy(int xPos, int yPos, int xSpeed, int ySpeed, int enemyType, Color color, int width, int height){
    super(xPos, yPos, xSpeed, ySpeed, color);
    this.enemytype = enemyType;//used later to determine image and movement
    this.width = width;
    this.height = height;
  }
  
  /**
   * Returns the hit box of the object aka its area of existance
   * WARNING THE BOSS SPRITE IS NOT 40 x 40, its 150 x 150
   * @return enemyHitBox is the rectange located at the enemy's coordinates that passes its width and height
   */
  public Rectangle getBounds() {
    Rectangle enemyHitBox = new Rectangle(this.getX(), this.getY(), width, height);//all enemy sprites will be 40x40
    return enemyHitBox;
  }
  
  /**
   * an override
   * Moves the enemy down depeneding on their speed, enemies do not need to move left and right
   */
  public void move() {
    y += ySp;
    x += xSp;
  }
  
  /**
   * Returns the enemy type
   * @return the integer that represent the enemy type
   */
  public int getType(){
    return this.enemytype;
  }
  
  /**
   * override
   * draws the image of the enemy depending on their type
   * @param g the graphics instance that will call this
   */
  public void draw(Graphics g) {
    // Variant 1
    if (this.enemytype == 1) {
      alien1.paintIcon(null, g, this.getX(), this.getY());
      // Variant 2
    } else if (this.enemytype == 2) {
      alien2.paintIcon(null, g, this.getX(), this.getY());
      // Variant 3
    } else if (this.enemytype == 3) {
      alien3.paintIcon(null, g, this.getX(), this.getY());
    } //variant 4
    else if(this.enemytype == 4){
      alien4.paintIcon(null, g, this.getX(), this.getY());
    }//boss 
    else if (this.enemytype == 5) {
      boss.paintIcon(null, g, this.getX(), this.getY());
    }
  }
}
