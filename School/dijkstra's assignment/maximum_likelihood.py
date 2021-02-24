'''
CSC373 Fall 2020
Assignment 5
University of Toronto Mississauga
'''

# Do NOT add any "import" statements
# Do NOT use Python dictionaries

# a heap implementation used for dijkstra
# all methods are log n, explained above each method
class myHeap:
    def __init__(self):
        self.heap = [0] # our heap has a root at 1, this is to make indeces easier to deal with
    """
    helpers:
    left, right,parent,min_child, size

    operations:
    bubble_up, bubble-down, push, extract_min

    """
    #return probability (aka weight/priority) of a node
    # complexity: O(1) - array access
    def getval(self,idx):
        return self.heap[idx][1]

    # nodes are stored as (end_point,probability)
    # complexity: O(1) - array access
    def makeNode(self, edge):
        return [edge[0],edge[1]]

    # complexity: parent, left, right, and size are all O(1)
    # it is either a calcualtion or len() which is O(1)
    # each one just returns an index corresponding to the name of the method

    def parent(self, pos):
        return pos // 2

    def left(self, pos):
        return pos * 2

    def right(self, pos):
        return 1 + pos * 2

    def size(self):
        return len(self.heap) - 1


    #get the smaller left or right child of an index
    # used in bubble down
    # complexity: O(1) since its just array accesses and comaprisons
    def min_child(self, item):
        left = self.left(item)
        right = self.right(item)
        # if left is smaller than right return left
        # if right is out of index then we have to return left
        if (right > self.size()) or (self.getval(left) < self.getval(right)):
            return left
        return right


    #bubble down is like a part of heapify
    #used to keep heap property intact
    #takes a node at index i, typically index 1, and moves it down to the propper position in heap
    # complexity: O(log n) since we bubble down makes a node go down one branch of a graph
    # that is at most the height of the tree and not the whole graph it is O(log n)
    def bubble_down(self, item):
        index = item
        left = self.left(index)
        while left <= self.size():
            child = self.min_child(index)
            # if current is bigger than child it can go down still
            if self.getval(index) > self.getval(child):
              # swap, aka bubble
                self.heap[child], self.heap[index] = self.heap[index], self.heap[child]
            # keep moving down, update index
            index = child
            left = self.left(index)


    #Bubble up is like heapify
    # keeps the heap propery intact
    # takes at node at index i, typically the newest node at the end, and moves it up to the propper position in heap
    # complexity: similar to bubble down, since it makes a node go up a branch that is at most equal to the height
    # of the tree it is O(log n)
    def bubble_up(self, item):
        index = item
        parent = self.parent(index)
        # if current < parent then it can go up still
        # parent has to exist within bounds, though
        while parent > 0 and self.getval(index) < self.getval(parent):
            self.heap[index], self.heap[parent] = self.heap[parent], self.heap[index]
            index = parent
            parent = self.parent(index)


    #add new node to heap
    #we add it to the end and just bubble up, since we add it to the end
    # complexity: O(log n): involves append and bubble up
    # append is O(1) and bubble up is O(log n)
    def push(self, item):
        node = self.makeNode(item)
        self.heap.append(node)
        last = self.size()
        self.bubble_up(last)


    #extract min is more or less the most important function
    # extracts the root aka smallest value node and then reorganizes the heap
    # complexity: involves pop and bubble down
    # pop, with no arguments, is O(1) and bubble down is O(log n)
    def extract_min(self):  # what
        res = self.heap[1]
        replace = self.heap.pop()
        if self.size() > 0:
            self.heap[1] = replace
            self.bubble_down(1)
        return res


# makes an adjacency list with probabilities
#g is list of edges, v is # of vertices
# we do range v+1 because 0 will never be used due to our heap implementation
# the way this adjacency list is made is very similar to a dictionary as index i  is vertex i
# and graph[i] will have a list of paths involving i as the starting point
# complexity: O(E) as we append an item for every item in the list of edges
def create_prob(g, v):
  al = [[] for x in range(v + 1)]
  for tup in g:
    # we store only the destiantion and the probability
    # this is because the index of al is the source
    al[tup[0]].append([tup[1], tup[2]])
  return al


# this is a helper function to make the ideal path
# complexity: O(V)
def paths(path,source,end):
  track = end
  res = []
  if path[end] == -1: # we could not get to the end
    return []
  res.append(end)
  # trace backwards from the ending vertice
  # tracing abck all vertices (worst case) means O(V)
  while track != source:
    track = path[track]
    res.append(track)
  #this reversal is also O(V) but that just means 2*O(V) = O(V)
  return res[::-1]


# builds off of dijkstra's algorithm using a personal heap implementation
# complexity: (E log V) as we will explain as we go
# tl:dr for complexity: O(m log n) + O(log v) + O(n) + O(m) + O(1) = O(m log n)
def maximum_likelihood(num_vertices, edges, s, t):
  '''
  Pre: num_vertices, edges, start vertex s, target vertex t
  Post: return the path that maximizes the probability
		of a successful traversal and its weight
  '''

  # this is a quick check if source is also our end, just to save time
  if s == t:
    return [s], 1

  # initialize all the necessary data structures
  graph = create_prob(edges,num_vertices) # O(E)
  heap = myHeap() # make empty list
  # its dist but really probability
  dist = [0] * (num_vertices + 1) # O(V)
  dist[s] = 1 # 0 chance of failure since you start there
  path = [-1] * (num_vertices + 1) # O(V)
  heap.push((s,1)) # recall a push is O(log v)

  # only things pushed to heap are edges: thus O(E) * operations within
  while heap.size() > 0:
    # retrieve the current best vertex
    node,p = heap.extract_min() # recall that this is O(log v)

    #this loop only retrieves the edges, so despite the existance of
    # a loop within here, it is still O(E)
    for vertex, prob in graph[node]:
      # this gets every neighbour of graph node aka every edge
      # recall, as stated above, that this entire block
      # iterates for at most, O(e)

      if dist[vertex] < p * prob:
        dist[vertex] = p * prob # get the probability after crossing the path
        heap.push((vertex,p*prob)) # O(log v)
        path[vertex] = node # if this path is better, we record it as part of the ideal path
      # thus, we have O(E) * O(log v) = O(e log v)
  # paths is a helper used to get the ideal path, it is O(V)
  return paths(path,s,t),dist[t]

# I commented out the main stuff
# also added some test cases thanks to the forums

  # # Uncommenting the following lines will result in Case 1 passing
  # path = [1,3,4]
  # prob = 0.15
  # return path, prob
if __name__ == '__main__':

  # some small test cases
  # Case 1
  print(maximum_likelihood(3,[[1,2,0.5],[2,3,0.5],[1,3,0.3]],1,3) == ([1,3],0.3))
  print(maximum_likelihood(2, [[1,2,0]], 1, 2) == ([], 0))
  print(maximum_likelihood(1, [], 1, 1) == ([1], 1))
  print(maximum_likelihood(1, [[1,1,0.2], [1,1,0.5]], 1,1) == ([1], 1))
  print(maximum_likelihood(2, [[1,2,0.2], [1,2,0.5]], 1, 2) == ([1,2], 0.5))
  print(maximum_likelihood(2, [[1,2,0.2], [1,2,0.5]], 2, 1) == ([], 0))
  print(maximum_likelihood(6, [[1,2,0.9], [2,3,0.9], [3,5,0.9], [2,5,0.5]], 2, 5) == ([2,3,5], 0.81))
  print(maximum_likelihood(6, [[3,1,0.9], [1,3,0.9], [3,5,0.9]], 3, 5) == ([3,5], 0.9))
  print(maximum_likelihood(5, [[1,4,0.36], [1,3,0.8], [2,1,0.1], [2,3,0.1], [3,2,0.5], [2,4,0.9], [5,1,1], [4,1,0.2], [1,4,0.04]], 5, 4) == ([5,1,3,2,4], 0.36000000000000004))
  print(maximum_likelihood(5, [[1,4,0.37], [1,3,0.8], [2,1,0.1], [2,3,0.1], [3,2,0.5], [2,4,0.9], [5,1,1], [4,1,0.2], [1,4,0.04]], 5, 4) == ([5,1,4], 0.37))
  assert ([1, 3, 4], 0.15) == maximum_likelihood(
    4,
    [[1,2,0.2], [1,3,0.5], [1,4,0.1], [2,4,0.5], [3,4,0.3]],
    1,
    4
    )
