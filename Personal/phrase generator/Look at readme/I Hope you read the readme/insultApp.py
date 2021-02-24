import tkinter as tk
import time as tm
import random as rnd

class Reader:
    def __init__(self):
        self.inp = None
        self.storage = {}
        self.previous = []
        self.prevForm = -1

    def getInp(self, name):
        self.inp = open(name,"r")
        self.build(self.inp)
        self.inp.close()

    def build(self,lines):
        curr = None
        for line in lines:
            if line == "EOF" or line == "EOF\n":
                break
            line = line[0:-1]
            if "//" in line or line == '':
                pass
            elif '#' in line:
                curr = line[1:]
                self.storage[line[1:]] = []
            else:
                self.storage[curr].append(line)

    def storePrev(self, lst):
        self.previous = []
        for x in lst:
            self.previous.append(x)

    def generate(self):
        sysrand = rnd.SystemRandom(tm.time())
        formats = sysrand.randint(0,6)
        while self.prevForm == formats:
            formats = sysrand.randint(0,6)
        self.prevForm = formats
        chooser = rnd.randint(0,123)
        result = {0: (lambda x: self.ASN(x)),
                        1: (lambda x: self.NLS(x)),
                        2: (lambda x: self.ASO(x)),
                        3: (lambda x: self.Cunt(x)),
                        4: (lambda x: self.SF(x)),
                        5: (lambda x: self.montyShakes(x)),
                        6: (lambda x: self.NF(x))
                        }[formats](chooser)
        return result

    def ASN(self, seed):
        sysrand = rnd.SystemRandom(seed)
        while True:
            a = sysrand.randint(0,len(self.storage["Adjective"])-1)
            s = sysrand.randint(0,len(self.storage["Swear"])-1)
            n = sysrand.randint(0,len(self.storage["Noun"])-1)
            if a not in self.previous and s not in self.previous and n not in self.previous:
                break
        self.storePrev([a,s,n])
        res = self.storage["Adjective"][a] + " "
        res += self.storage["Swear"][s] + " "
        res += self.storage["Noun"][n]
        return "You " + res

    def NLS(self, seed):
        sysrand = rnd.SystemRandom(seed)
        while True:
            s = sysrand.randint(0,len(self.storage["Swear"])-1)
            n = sysrand.randint(0,len(self.storage["Noun"])-1)
            if s not in self.previous and n not in self.previous:
                break
        self.storePrev([s,n])
        res = self.storage["Noun"][n] + " Looking " + self.storage["Swear"][s]
        return "You " + res

    def ASO(self,seed):
        sysrand = rnd.SystemRandom(seed)
        while True:
            a = sysrand.randint(0, len(self.storage["Affilation"])-1)
            s = sysrand.randint(0, len(self.storage["Swear"])-1)
            o = sysrand.randint(0, len(self.storage["Object"])-1)
            if a not in self.previous and s not in self.previous and o not in self.previous:
                break
        self.storePrev([a,s,o])
        res = self.storage["Affilation"][a] + " "
        res += self.storage["Swear"][s] + " "
        res += self.storage["Object"][o]
        return "You " + res

    def Cunt(self,seed):
        sysrand = rnd.SystemRandom(seed)
        while True:
            c = sysrand.randint(0, len(self.storage["Cunt"]) - 1)
            if c not in self.previous:
                break
        self.storePrev([c])
        return "You " + self.storage["Cunt"][c]

    def SF(self,seed):
        sysrand = rnd.SystemRandom(seed)
        while True:
            s = sysrand.randint(0, len(self.storage["Swear"]) - 1)
            f = sysrand.randint(0, len(self.storage["Food"]) - 1)
            if s not in self.previous and f not in self.previous:
                break
        self.storePrev([s, f])
        res = self.storage["Swear"][s] + " "
        res += self.storage["Food"][f]
        return "You " + res

    def montyShakes(self,seed):
        sysrand = rnd.SystemRandom(seed)
        while True:
            s = sysrand.randint(0, len(self.storage["Shakespearean"]) - 1)
            if s not in self.previous:
                break
        self.storePrev([s])
        res = self.storage["Shakespearean"][s]
        return res

    def NF(self,seed):
        sysrand = rnd.SystemRandom(seed)
        while True:
            n = sysrand.randint(0, len(self.storage["Noun"]) - 1)
            if n not in self.previous:
                break
        self.storePrev([n])
        res = self.storage["Noun"][n]
        return "You " + res + " Fucker"

class Application:
    def __init__(self, root, generator):
        self.generator = generator
        self.root = root
        text = self.generator.generate()
        self.colors = ['red', 'green', 'blue', 'orange', 'cyan', 'purple', 'black', 'brown']
        self.label = tk.Label(root,text=text, fg='red', font=("Helvetica", 18))
        self.label.place(x=250, y=200,anchor='center')
        root.wm_title("LMAO")
        root.geometry("500x500")
        self.button = tk.Button(root, text="Generate Insult", command= self.refresh)
        self.button.place(relx=0.5, rely=0.5, anchor="center")
        self.colorPrev = None

    def refresh(self):
        text = self.generator.generate()
        self.label.destroy()
        color = rnd.choice(self.colors)
        while self.colorPrev == color:
            color = rnd.choice(self.colors)
        self.colorPrev = color
        self.label = tk.Label(self.root, text=text, fg=color, font=("Helvetica", 18))
        self.label.place(x=250, y=200, anchor='center')
        return

if __name__ == '__main__':
    test = Reader()
    test.getInp("input.txt")
    window = tk.Tk()
    app = Application(window, test)
    window.after(1, app.refresh())
    window.mainloop()
