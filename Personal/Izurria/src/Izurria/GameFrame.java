package Izurria;


/**
 * @Author Miguel De Vera
 * @Version 2018-01-01
 */

import java.awt.BorderLayout;
import java.awt.EventQueue;
import java.awt.Color;

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

import javax.sound.sampled.AudioSystem;
import javax.sound.sampled.Clip;
import javax.sound.sampled.AudioInputStream;

/**
 * the Jframe that hosts the game's JPanel
 * has a Jpanel that is a gamepanel
 */
public class GameFrame extends JFrame{
  private GamePanel game;
  
  /**
   * Launch the application.
   * @param args its the string array that holds the code, im only doing this to appease the java doc gods
   */
  public static void main(String[] args) {
    GameFrame tomato = new GameFrame();
  }
  
  /**
   * Creates the frame.
   * sets the title of the frame to Izurria
   * closes if x on windows is pressed
   * intializes a gamepanel and adds to the frame
   * is not resizable
   * is visible
   * calls the play sound method
   */
  public GameFrame() {
    super("Izurria");
    setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
    game = new GamePanel();
    this.getContentPane().add(game);
    this.pack();
    this.setResizable(false);
    this.setLocationRelativeTo(null);
    this.setVisible(true);
    //playSound();
    //due to the size of the music file, I have disabled the background music
  }
  /**
   * loops the background music 
   * btw the music is End of the Dark from policenauts and is hella dope
   */
  public static void playSound() {
    try {
      Path path = FileSystems.getDefault().getPath("").toAbsolutePath();
//    System.out.println("Warning this is a test");
//    System.out.println(path);
//    these all work so thats pretty cool
//    final String dir = System.getProperty("user.dir");
//    System.out.println("current dir = " + dir);
      File audio = new File(path + "/src/Izurria/backgroundMusic.wav");
      AudioInputStream audioInputStream = AudioSystem.getAudioInputStream(audio);
      Clip clip = AudioSystem.getClip();
      clip.open(audioInputStream);
      clip.start();
      clip.loop(Clip.LOOP_CONTINUOUSLY);
    } catch(Exception ex) {
      System.out.println("Error with playing sound.");
      ex.printStackTrace();
    }
  }//clip.stop can pause sound, make it so that if controller = m, does loop method ONCE, if m again does stop ONCE
}
