package Izurria;


/**
 * @Author Miguel De Vera
 * @Version 2018-01-01
 */
//add an hp recovery powerup, spread shot, a bigger bullet, a boom power up - like COD Kaboom!, speed power up/ enemy slowdown power up
//powerups will be an enemy object that moves down, rng for them will only occur if no abiitities are on

import javax.swing.*;
import javax.swing.Timer;//needs to be specifically imported

import java.util.*;// as to not be confused with util
import java.io.*;
import java.nio.file.FileSystems;
import java.nio.file.Path;
import java.awt.*;
import java.awt.event.*;

import sun.audio.AudioPlayer;
import sun.audio.AudioStream;

/**
 * The game "canvas" that has all the objects painted on it
 * Is double buffered by default, 
 */
public class GamePanel extends JPanel {
  // Required components.
  private Timer gameTimer;
  private Controller controller;
  // Controls size of game window and framerate
  private final int WIDTH = 800;
  private final int HEIGHT = 800;
  private final int FPS = 120;
  
  //Font stuff
  private Font font = new Font("Arial", Font.BOLD,12);
  
  //Counters and variables
  private int score = 4900;
  private int numberOfLives;
  private int highScore;
  private int markerX, markerY;//debug tool
  private int maxEnemy;
  private int rng;
  private int speedEnemy;
  private int bossHp;
  private int scoreMilestone;
  private int timeElapsed;
  private int bulletCooldown;
  private int pauseDelay;
  private int speedMilestone;
  
  //initilaze the highscore file and writer
  File file = new File("Scores.txt");
  File f = new File("Highscore.txt");
  FileWriter fw;
  BufferedWriter bw;
  
  //Objects
  private Ship playerShip;
  private Enemy enemy;
  private Enemy boss;
  private Bullet bullet;
  private Bullet beam;
  
  // Booleans
  private boolean newBulletCanFire = true;
  private boolean hitMarker = false;//an enemy is hit, no longer needed, i think... keep it here just in case for debugging
  private boolean alive;
  private boolean continues = true;
  private boolean bossSpawn;
  
  //Array Lists
  private ArrayList<Enemy> enemyList = new ArrayList<Enemy>();
  private ArrayList<Bullet> bulletList = new ArrayList<Bullet>();
  private ArrayList<Enemy> pauseEnemy = new ArrayList<Enemy>();
  
  // Added Audio files and streams
  Path path = FileSystems.getDefault().getPath("").toAbsolutePath();
  private File bulletSound = new File(path + "/src/Izurria/" + "bulletSound.wav");
  private File deathSound = new File(path + "/src/Izurria/" + "deathSound.wav");
  private File hitmarkerSound = new File(path + "/src/Izurria/" + "hitmarkerSound.wav");
  private File beamSound = new File(path + "/src/Izurria/" + "alienBeam.wav");
  private File levelUp = new File(path + "/src/Izurria/" + "levelUpSound.wav");
  private File damageSound = new File(path + "/src/Izurria/" + "damageSound.wav");
  private AudioStream bulletSoundAudio;
  private InputStream bulletSoundInput;
  private AudioStream beamSoundAudio;
  private InputStream beamSoundInput;
  private AudioStream deathSoundAudio;
  private InputStream deathSoundInput;
  private AudioStream hitSoundAudio;
  private InputStream hitSoundInput;
  private AudioStream damageSoundAudio;
  private InputStream damageSoundInput;
  
  private AudioStream levelUpAudio;
  private InputStream levelUpInput;
  
  //Imaage for the background
  private ImageIcon background = new ImageIcon(path + "/src/Izurria/" + "ezgif.com-resize.gif");
  //image for a logo because why not
  private ImageIcon logo = new ImageIcon(path + "/src/Izurria/" + "logo.gif");
  
  //Things to do: create a render method, a tick method, a setup method and a startgame method
  /////////////////////////////////////////////////////////////////CONSTRUCTOR/////////////////
  public GamePanel(){
    // Set the size of the Panel
    this.setSize(WIDTH, HEIGHT);
    this.setPreferredSize(new Dimension(WIDTH, HEIGHT));
    this.setBackground(Color.BLACK);
    
    // Register KeyboardController as KeyListener
    controller = new Controller();
    this.addKeyListener(controller);
    // Call setupGame to initialize fields
    this.setup();
    this.setFocusable(true);// your KeyEvent will only be dispatched to the panel if it is "focusable" and it has focus.
    this.requestFocusInWindow();
    
    gameTimer = new Timer(1000/FPS, timer);//the one is used instead of 1000/FPS becuase i want it to update as fast as possible, if problems or fps adjustment is needed
    gameTimer.start();// then FPS will remain
    
  }
  /////////////////////////////////////////////////////////////////CONSTRUCTOR/////////////////
  /////////////////////////////////////////////////////////////////SET UP/////////////////
  public void setup(){
    // Resets all controller movement in case someone was pushing buttons
    controller.resetController();
    //inititalize player ship
    playerShip = new Ship(WIDTH / 2, (HEIGHT * 4) / 5, null, controller);
    //create lives, 3 base
    numberOfLives = 200;
    //sets an alive boolean, used for debbuging
    alive = true;
    // scans high score
    try {
      Scanner fileScan = new Scanner(f);
      while (fileScan.hasNextInt()) {
        String nextLine = fileScan.nextLine();
        Scanner lineScan = new Scanner(nextLine);
        highScore = lineScan.nextInt();
      }
    } catch (FileNotFoundException e) {
    }
    //The spooky intro panel
    JOptionPane.showMessageDialog(null, "Welcome to IZURRIA!\nThe galaxy is in danger!\n Do your best to kill the threat!\nINSTRUCTIONS:\n\n- Use W/A/S/D keys to move UP/LEFT/DOWN/RIGHT.\n- Press spacebar to shoot\n- Press p to pause the game\n- The enemies get faster as time passes."
                                    + "\n- Enemies will also shoot lasers.\n- More enemies will spawn as your score increases\n- If an enemy or its laser touches you, your HP will decrease.\n -A boss will appear at certain points"
                                    + "\n This is an endless game, do your best!\n\nHAVE FUN!");
    enemyList.clear();
    timeElapsed = 0;
    speedEnemy = 1;
    score = 0;
    scoreMilestone = 5000;
    speedMilestone = 60;
    bossSpawn = false;
    bossHp = 0;
    //the enemy examples for the pause menu
    for(int i = 0; i<= 5;i++){
      if(i !=5)
        enemy = new Enemy(100, 240 + (i * 60), 0, maxEnemy, i, null, 40, 40);//every so often it will becomes faster
      else
        enemy = new Enemy(450, 300, 0, maxEnemy, i, null, 40, 40);//every so often it will becomes faster
      pauseEnemy.add(enemy);
    }
  }
  /////////////////////////////////////////////////////////////////SET UP/////////////////
  /////////////////////////////////////////////////////////////////PAINT/////////////////
  /**
   * override
   * paints EVERYTHING
   * will be called in repaint()
   * @param g is the graphics instance that paints the objects
   */
  public void paint(Graphics g){//graphics g, score, numberoflives, max enemy, highscore, speed enemy, max enemy, time elapsed, boss hp
    if(continues){
      drawUI(g);
      playerShip.draw(g);
      //paint bullet
      if(bullet != null){
        bullet.draw(g);
      }
      //paint enemies
      for (int i = 0; i < enemyList.size(); i++) {
        enemyList.get(i).draw(g);
      }
      if(bulletList.size() > 0){
        for (int i = 0; i < bulletList.size(); i++) {
          bulletList.get(i).draw(g);
        }
      }
    }
    else{//this is going to be a pause menu
      drawPause(pauseEnemy, g);
    }
  }
  /////////////////////////////////////////////////////////////////PAINT/////////////////
  /////////////////////////////////////////////////////////////////TICK/////////////////
  /**
   * updates the game logic
   * updates all object behavior
   */
  public void tick(){
    //the pause button
    if (controller.getKeyStatus(KeyEvent.VK_P)) {
      if(continues && pauseDelay >= 10){
        continues = false;
        pauseDelay = 0;
      }
      else if(!continues && pauseDelay >= 10){
        continues = true;
        pauseDelay = 0;
      }
    }
    pauseDelay++;
    //if player lives are not zero run the game, if it is game over and score update
    if(numberOfLives > 0 && alive && continues){
      if(continues){
        timeElapsed++;
        playerShip.move();
        //check if spacebar has been pressed, if so new bullet
        if (controller.getKeyStatus(KeyEvent.VK_SPACE)) {
          if (newBulletCanFire && bulletCooldown >= 32) {//its 32 because 1000/fps is about 8, which means 1 frame is a vlaue of 8, 4 frames is a value of 32
            fireBullet();
          }
        }
        bulletCooldown++;
        //if there is a bullt make it go up, if bullet y < 0, remove bullet from array list or make its reference null
        if (bullet != null) {
          bullet.setY(bullet.getY() - 15);//this is a fast bullet bois
          if (bullet.getY() < 0) {
            newBulletCanFire = true;
            bullet = null;
          }
        }
        maxEnemy = (score/5000) + 1;//(updates enemy modifier)
        if(maxEnemy > 10)
          maxEnemy = 10;
        //check if bullet has hit an enemy, if so add points and kill both
        //if you want the shot to be piercing comment out A and B
        if(bullet != null){
          for(int i = 0; i < enemyList.size();i++){
            if(bullet.isIntersecting(enemyList.get(i))){//PROBLEM
              bullet = new Bullet(0, 0, 0, null);//A
              for(int j = 0; j < maxEnemy;j++){//max enemy is the socre modifier it adds 100*score modifier
                if(bossHp <= 0)
                  score += 100;
                if(score%scoreMilestone == 0 && !bossSpawn && bossHp == 0){
                  bossSpawn = true;
                }
              }//enemy modifier is also score modifier
              newBulletCanFire = true;//B
              AudioPlayer.player.start(hitSoundAudio); // Plays hitmarker sound if you hit an enemy
              if(bossHp <= 0)//clears enemies
                enemyList.remove(i);
              else{
                bossHp -= 1;//checks bosses
                if(bossHp <= 0){
                  enemyList.clear();
                  for(int j = 0; j < maxEnemy*2;j++){
                    score += 100;
                  }
                  numberOfLives += 100;
                  scoreMilestone *= 2;
                  AudioPlayer.player.start(levelUpAudio); // Plays hitmarker sound if you hit an enemy
                }
              }
            }
          }
        }
        if(bossSpawn)
          bossHp = 5 + (maxEnemy * 2);
        //clear enemy list and spawn boss if spawwn boss is true
        if(bossSpawn){
          enemyList.clear();
          spawnBoss(enemyList, maxEnemy);
          bossSpawn = false;
          bulletList.clear();
        }
        //spawn an enemy in a random area if the array is less than army size, base of six increases as score does, boss hp must be zero
        while(enemyList.size() < 6 + maxEnemy && bossHp <= 0){
          if(enemyList.size() < 6 + maxEnemy){
            rng = (int)(Math.random() * 4) + 1;
            if(rng == 1)
              spawnRight(enemyList, maxEnemy);
            if(rng == 2)
              spawnLeft(enemyList, maxEnemy);
            if(rng == 3)
              spawnStraight(enemyList, maxEnemy);
            if(rng == 4)
              spawnTraitor(enemyList, maxEnemy);
          }
          ////makes sure no straight enemies are spawned when near the border
          for(int i = 0; i < enemyList.size();i++){
            if(enemyList.get(i).getX() >= 760 && enemyList.get(i).getType() == 3){
              enemyList.remove(i);
            }
          }
        }
        //move enemies in entire array down, use a for loop
        if(timeElapsed%((FPS/2)*speedMilestone) == 0)
          speedEnemy++;
        //speedEnemy = (score/5000) + 1;
        if (speedEnemy > 8)
          speedEnemy = 8;
        //move only enemies
        try{
          if(bossHp <= 0){
            for(int i = 0; i < enemyList.size(); i++){
              if(enemyList.get(i).getType() == 4 && i % 2 == 0)
                enemyList.get(i).setXSpeed(2);//makes traitor ship move to the right
              else if(enemyList.get(i).getType() == 4)
                enemyList.get(i).setXSpeed(- 2);//makes traitor ship move to the left
              enemyList.get(i).move();
              
            }
          }
        }catch(IndexOutOfBoundsException e){}
        //move boss
        if(bossHp > 0){
          for(int i = 0; i < enemyList.size(); i++){
            enemyList.get(i).move();/////////BOSS MOVEMENT IS CONTROLLED HERE
          }
        }
        //if enemies y > height, its gone, aka if enemy goes off screen, its gone
        checkBoundry(enemyList);
        //if an enemy collides with the player, lose one HP
        for(int i = 0; i < enemyList.size(); i++){
          if(enemyList.get(i).isIntersecting(playerShip))
            numberOfLives -= 1;
        }
        if(numberOfLives <= 0)
          alive = false;
        //make enemies shoot beams
        try{
          if(bulletList.size() <= 0){
            rng = (int)(Math.random() * 9) + 1;
            for(int i = 0; i < enemyList.size();i++){
              if(i%rng == 0){
                beam = new Bullet(enemyList.get(i).getX() + 20,enemyList.get(i).getY(),0, Color.YELLOW);
                bulletList.add(beam);
                if(bossHp > 0){
                  beam = new Bullet(enemyList.get(i).getX() + 80,enemyList.get(i).getY(),0, Color.YELLOW);
                  bulletList.add(beam);
                  beam = new Bullet(enemyList.get(i).getX() + 150,enemyList.get(i).getY(),0, Color.YELLOW);
                  bulletList.add(beam);
                }
                AudioPlayer.player.start(beamSoundAudio); // Plays bullet sound
              }
            }
          }
        }catch(IndexOutOfBoundsException e){}
        //updates beam behavior, movements and collision
        try{
          if(bulletList.size() > 0){
            for(int i = 0; i < bulletList.size();i++){
              bulletList.get(i).setY(bulletList.get(i).getY() + 10 + speedEnemy);
              if(bulletList.get(i).getY() > HEIGHT)
                bulletList.remove(i);
              if(bulletList.get(i).isIntersecting(playerShip)){
                bulletList.remove(i);
                if(bossHp > 0){
                  numberOfLives -= 200;
                  AudioPlayer.player.start(damageSoundAudio);
                }
                else{
                  numberOfLives -= 10;
                  AudioPlayer.player.start(damageSoundAudio);
                }
              }
            }
          }
        }catch(IndexOutOfBoundsException e){}
      }
    }
    
    //if player lives are zero, game stops 
    if(numberOfLives <= 0){
      try{
        continues = false;
        controller.resetController();
        System.out.println("You have lost! It's okay though, your score was: " + score + ", have a god day!");
        enemyList.clear();
        AudioPlayer.player.start(deathSoundAudio); // Plays death sound
        bullet = null;
        //updates HIGHSCORE
        try {
          if (score >= highScore) {
            String scoreString = Integer.toString(score);
            PrintWriter pw = new PrintWriter(new FileOutputStream(f, false));
            pw.write(scoreString);
            pw.close();
          }
        } catch (FileNotFoundException e) {
        }
        //Updatescore, regardless of pont value, hall of shame amiright
        String scoreString = Integer.toString(score);
        String name = JOptionPane.showInputDialog(null, "What's your name?");
        try{
          PrintWriter pw = new PrintWriter(new FileOutputStream(file, true));//The main reason to use the PrintWriter is to get access to the printXXX methods 
          pw.println();
          pw.println(name + " " + scoreString);//(like println(int)). You can essentially use a PrintWriter to write to a file just like you would use System.out to write to the console.
          pw.close();
        }catch(FileNotFoundException e){}
        System.exit(0);//closes the entire program, thanks SO
        
      }catch(NullPointerException e){
        System.exit(0);
      }
    }
    try {//obtains sounds and places them into streams to be called by players
      bulletSoundInput = new FileInputStream(bulletSound);
      bulletSoundAudio = new AudioStream(bulletSoundInput);
      
      beamSoundInput = new FileInputStream(beamSound);
      beamSoundAudio = new AudioStream(beamSoundInput);
      
      deathSoundInput = new FileInputStream(deathSound);
      deathSoundAudio = new AudioStream(deathSoundInput);
      
      hitSoundInput = new FileInputStream(hitmarkerSound);
      hitSoundAudio = new AudioStream(hitSoundInput);
      
      levelUpInput = new FileInputStream(levelUp);
      levelUpAudio = new AudioStream(levelUpInput);
      
      damageSoundInput = new FileInputStream(damageSound);
      damageSoundAudio = new AudioStream(damageSoundInput);
    } catch (IOException e) {
    }
  }
  /////////////////////////////////////////////////////////////////TICK/////////////////
  /**
   * @return numberOfLives as a debugging check
   */
  public int getLives(){//debugging tool, leave here if needed
    return numberOfLives;
  }
  /////////////////////////////////////////////////////////////////////////////////TIMER
  /**
   * the timer that runs the entire game
   * seriously do not mess with this
   * SERIOUSLY
   * runs the tick and repaint every interval
   */
  ActionListener timer = new ActionListener(){
    public void actionPerformed(ActionEvent e){
      tick();
      repaint();
    }
  };
  ///////////////////////////////////////////////////////////////////////////////////////TIMER
  
  /*
   * spawns straight enemy
   * @param e is the array list that holds the enemies
   * @param speed is the new YSpeed for the enemy
   */
  public void spawnStraight(ArrayList<Enemy> e, int speed){
    enemy = new Enemy((20 + (int)(Math.random() * WIDTH)), 0, 0, speed, 3, null, 40, 40);//every so often it will becomes faster
    enemyList.add(enemy);
  }
  
  /*
   * spawns traitor enemy
   * @param e is the array list that holds the enemies
   * @param speed is the new YSpeed for the enemy
   */
  public void spawnTraitor(ArrayList<Enemy> e, int speed){
    enemy = new Enemy((20 + (int)(Math.random() * WIDTH)), 0, 2, speed, 4, null, 40, 40);//every so often it will becomes faster
    enemyList.add(enemy);
  }
  
  /*
   * spawns right enemy
   * @param e is the array list that holds the enemies
   * @param speed is the new YSpeed for the enemy
   */
  public void spawnRight(ArrayList<Enemy> e, int speed){
    enemy = new Enemy((20 + (int)(Math.random() * WIDTH)), 0, 2, speed, 1, null, 40, 40);//every so often it will becomes faster
    enemyList.add(enemy);
  }
  
  /*
   * spawns left enemy
   * @param e is the array list that holds the enemies
   * @param speed is the new YSpeed for the enemy
   */
  public void spawnLeft(ArrayList<Enemy> e, int speed){
    enemy = new Enemy((20 + (int)(Math.random() * WIDTH)), 0, -2, speed, 2, null, 40, 40);//every so often it will becomes faster
    enemyList.add(enemy);
  }
  
  /*
   *spawns boss enemy
   * @param e is the array list that holds the enemies
   * @param speed is the new YSpeed for the enemy
   */
  public void spawnBoss(ArrayList<Enemy> e, int speed){
    enemy = new Enemy((20 + (int)(Math.random() * WIDTH)), 0, speed + 1, 0, 5, null, 150, 150);//every so often it will becomes faster
    enemyList.add(enemy);
  }
  
  /*
   *draws the pause screen
   * @param e is the array list that holds the enemies
   * @param g is the graphics componenet that will be used
   */
  public void drawPause(ArrayList<Enemy> e, Graphics g){
    //set background
    g.setColor(Color.BLACK);
    g.fillRect(0,0,WIDTH + 100,HEIGHT + 100);
    //draw text
    g.setColor(Color.WHITE);
    g.drawString("CREDITS" , WIDTH/2 - 20, 100);
    g.drawString("Code by: Miguel De Vera, Sprites by Nathan Hoey" , WIDTH/2 - 100, 120);
    g.drawString("Music from: Policenauts OST: End of The Dark" , WIDTH/2 - 100, 140);
    g.drawString("Background from Google and edited by Miguel De Vera" , WIDTH/2 - 100, 160);
    g.drawString("Special Thanks To Spartan Tech, who provided the sfx, " , WIDTH/2 - 100, 180);
    g.drawString("and the controller class code" , WIDTH/2 - 100, 200);
    g.drawString("Shout out to The Guy" , WIDTH/2 - 100, 220);
    //draw pause enemies
    for (int i = 0; i < e.size(); i++) {
      e.get(i).draw(g);
    }
    g.drawString("Huelawn: moves only to the right." , 145, 330);
    g.drawString("Mehfysto: moves only to the left." , 145, 390);
    g.drawString("Bahks: moves straight ahead." , 145, 450);
    g.drawString("Traitor Ship: changes movement" , 145, 510);
    g.drawString("Rompulas: an alien commander, shoots spread shots," , 400, 450);
    g.drawString("its shots deal massive damage but its death will heal you" , 400, 470);
  }
  
  /*
   *draws the UI elements
   * @param g is the graphics componenet that will be used
   */
  public void drawUI(Graphics g){
    //paint background
    background.paintIcon(null, g, 0, -150);
    //paint UI elements
    //paints the logo
    logo.paintIcon(null, g, 740, 10 );
    // sets font
    g.setFont(font);
    // *** Sets the score display
    g.setColor(Color.WHITE);
    g.drawString("SCORE: " + score, 180, 25);
    //*** Sets the life counter display
    g.drawString("HEALTH:" + numberOfLives, 20, 25);
    //prints max amount of enemies at a time
    g.drawString("ARMY SIZE: " + (maxEnemy + 6), 630, 25);
    // Sets Highscore display
    g.drawString("HIGHSCORE: " + highScore, 470, 25);
    // Sets Enemy speed display
    g.drawString("SPEED: " + speedEnemy, 110, 25);
    // Sets Enemy speed display
    g.drawString("SCORE MODIFIER: " + (maxEnemy * 100), 300, 25);
    // Sets time display
    g.drawString("TIME:" + timeElapsed / (FPS/2), 20, 40);
    //paint boss display if boss active
    if(bossHp > 0){
      g.setColor(Color.WHITE);
      g.drawString("BOSS HP:" + bossHp, 110, 40);
    }
  }
  
  /*
   *spawns a bullet object that is used by the player
   * plays the bullet sound when claeed
   * is essentially the bullet being shot'out of the player ship
   */
  public void fireBullet(){
    bullet = new Bullet(playerShip.getX()+ 20, playerShip.getY() - 20, 0, Color.RED);//-20 so its slighly above player ship
    AudioPlayer.player.start(bulletSoundAudio); // Plays bullet sound
    newBulletCanFire = false;
    bulletCooldown = 0;
  }
  
  /*
   * loops through the given array list to see if any enemies are colliding with the screen boundry
   * if they are then behaviours will be applied
   * if a ship moves all the way to the right, asuming a boss, rightShip or traitor ship, it sets it to the left side
   * if an enemy moves to the bottom of the screen it removes the enemy making way for new spawns
   * @param enemyList is the array list that holds the enemies
   */
  public void checkBoundry(ArrayList<Enemy> enemyList){
    for(int i = 0; i < enemyList.size(); i++){
      if (enemyList.get(i).getY() > HEIGHT && bossHp <= 0)
        enemyList.remove(i);
      else if(enemyList.get(i).getX() >=  WIDTH && bossHp <= 0)
        enemyList.get(i).setX(0);
      else if(enemyList.get(i).getX() <=  0  && bossHp <= 0)
        enemyList.get(i).setX(WIDTH );
      else if(enemyList.get(i).getX() > WIDTH - 140 && bossHp > 0){
        enemyList.get(i).setX(0);
      }
    }
  }
}