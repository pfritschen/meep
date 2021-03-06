"""
meeplib - A simple message board back-end implementation.

Functions and classes:

 * u = User(username, password) - creates & saves a User object.  u.id
     is a guaranteed unique integer reference.

 * m = Message(title, post, author) - creates & saves a Message object.
     'author' must be a User object.  'm.id' guaranteed unique integer.

 * get_all_messages() - returns a list of all Message objects.

 * get_all_users() - returns a list of all User objects.

 * delete_message(m) - deletes Message object 'm' from internal lists.

 * delete_user(u) - deletes User object 'u' from internal lists.

 * get_user(username) - retrieves User object for user 'username'.

 * get_message(msg_id) - retrieves Message object for message with id msg_id.

"""
import pickle
__all__ = ['Message', 'get_all_messages', 'get_message', 'delete_message',
           'User', 'get_user', 'get_all_users', 'delete_user']

###
# internal data structures & functions; please don't access these
# directly from outside the module.  Note, I'm not responsible for
# what happens to you if you do access them directly.  CTB

# a dictionary, storing all messages by a (unique, int) ID -> Message object.
_messages = {}
_words={}
_search=True
_searchIDs={}
### WHY DO DICTIONARYS STAY BUT BOOLEANS AND LISTS DO NOT??????????????????????????????????
_replies = {}

def saveMSG():
    filename = 'save.pickle'

    fp = open(filename, 'w')
    obj=[]
    print "SAVING MESSAGES"
    for n in _messages:
        m=get_message(n)
        try:
            obj.append(("Message",m.title, m.post, m.author.username, m.id))
            print "Message",m.title, m.post, m.author.username, m.id
        except:
            obj.append(("Message",m.title, m.post, m.author, m.id))
            print "Message",m.title, m.post, m.author, m.id



    pickle.dump(obj, fp)
    fp.close()
def saveUSER(username, password, uid):
    filename = 'users.pickle'

    fp = open(filename, 'w')
    obj=(username,password,uid)
    pickle.dump(obj, fp)
    fp.close()
def saveREPLY():
    filename = "replies.pickle"
    obj=[]
    fp = open(filename, 'w')
    for r in _replies:
        obj.append((r,_replies[r]))
        print "SAVING REPLY", r, _replies[r]
    
    pickle.dump(obj, fp)
    fp.close()
def loadMSG(save):
    Message(save[1], save[2], save[3],save[4])
    
def loadUSER():
    filename = 'C:/Users/Paul/meep/users.pickle'
    fp = open(filename)
    obj = pickle.load(fp)
    User(obj[0],obj[1],obj[2])
    print "LOADED USER",obj[0],obj[1],obj[2]
    
def load():
    filename = 'C:/Users/Paul/meep/save.pickle'
    fp = open(filename)
    obj = pickle.load(fp)
    for save in obj:
        if save[0]=="Message":
            loadMSG(save)
    fp2=open("C:/Users/Paul/meep/replies.pickle")
    obj=pickle.load(fp2)
    print "REPLY OBJ", obj
    h=0
    for reply in obj:
        print "REPLY", reply
        i=0
        for msg in reply[1]:
            
            print "LOADING REPLY",reply[0],reply[1][i]
            add_reply(reply[0],reply[1][i])
            i+=1
        h+=1
   
    
def _get_next_message_id():
    if _messages:
        return max(_messages.keys()) + 1
    return 0

# a dictionary, storing all users by a (unique, int) ID -> User object.
_user_ids = {}

# a dictionary, storing all users by username
_users = {}

def _get_next_user_id():
    if _users:

        x= max(_users.values())

        return x.id + 1
    return 0

def _reset():
    """
    Clean out all persistent data structures, for testing purposes.
    """
    global _messages, _users, _user_ids
    _messages = {}
    _users = {}
    _user_ids = {}

###

class Message(object):
    """
    Simple "Message" object, containing title/post/author.

    'author' must be an object of type 'User'.
    
    """
    def __init__(self, title, post, author, msgid):
        self.title = title
        self.post = post
        self.id=msgid
   ###COMMENTED BAD     assert isinstance(author, User)
        self.author = author
        if self.id == -1:
            self._save_message()
        else:
           _messages[self.id] = self
        add_message_to_dict(self)
        

    def _save_message(self):
        self.id = _get_next_message_id()
        
        # register this new message with the messages list:
        _messages[self.id] = self

def get_all_messages(sort_by='id'):
    return _messages.values()

def get_message(id):
    print "Messages", _messages
    return _messages[id]

def delete_message(msg):
    assert isinstance(msg, Message)
    remove_message_from_dict(msg)
    del _messages[msg.id]



    
def add_message_to_dict(msg):
    print "My message id"+ str(msg.id)
    print type(msg.id)
    message=_messages[msg.id]
    wordset=set()
    thePost=message.post.split()         ###search the replies dict to find replies, add their words to the worset
    for word in thePost:
        wordset.add(word)
    theTitle=message.title.split()
    for word in theTitle:
        wordset.add(word)
    
    print "THE WORDSET"
    print wordset
    for word in wordset:
        if word not in _words:
            temp=list()
            temp.append(msg.id)
            _words[word]=temp
        else:
            currentValue=_words[word]
            currentValue.append(msg.id)
            print "CURRENT VALUE"
            
            _words[word]=currentValue
            print  _words[word]

    return True

def remove_message_from_dict(msg):
    message=_messages[msg.id]
    wordset=set()
    thePost=message.post.split()
    for word in thePost:
        wordset.add(word)
    theTitle=message.title.split()
    for word in theTitle:
        wordset.add(word)
    for reply in _replies:
        print "REPLY", reply, '\n'
        print "msgid", msg.id
        if reply==msg.id:
            for word in _replies[reply]:
                wordset.add(word)
    for word in wordset:
        print "words",_words
        currentValue=_words[word]
        currentValue.remove(msg.id)
        _words[word]=currentValue

        #also removes words from its replies
    return True

def search_message_dict(text):
    text=text.split()
    searchSet=set()
    resultIDSet=set()
    for word in text:
        searchSet.add(word)
    for word in searchSet:
        if word in _words:
            for msgID in _words[word]:
                resultIDSet.add(msgID)

    print "THE SEARCH RESULTS"
    for msgID in resultIDSet:
        print msgID
    _searchIDs["test"]=resultIDSet
    print _searchIDs
    return resultIDSet

def get_search_results():
    print "Search",_searchIDs
    if "test" in _searchIDs:
        return _searchIDs["test"]
    else:
        return{}
    

def add_reply(message_id, reply):
    if _replies.has_key(message_id):
        _replies[message_id].append(reply)        
    else:
        _replies[message_id] = [reply]
        
    wordset=set()
    thePost=reply.split()         ###search the replies dict to find replies, add their words to the worset
    for word in thePost:
        wordset.add(word)
        
        for word in wordset:
            
            if word not in _words:
                temp=list()
                temp.append(message_id)
                _words[word]=temp
            else:
                currentValue=_words[word]
                currentValue.append(message_id)
                print "CURRENT VALUE"
            
                _words[word]=currentValue
                print  _words[word]

def has_replies(message_id):
    return _replies.has_key(message_id)

def get_replies(message_id):
    return _replies[message_id]
def delete_reply(message_id):
    print "replies", _replies
    if message_id in _replies:
        _replies.pop(message_id)


###

class User(object):
    def __init__(self, username, password,uid):
        self.username = username
        self.password = password
        if uid==-1:
            self._save_user()
        else:
            _users[self.username] = self
    def _save_user(self):
        self.id = _get_next_user_id()

        # register new user ID with the users list:
        _user_ids[self.id] = self
        _users[self.username] = self
        

def get_user(username):
    return _users.get(username)         # return None if no such user

def get_all_users():
    return _users.values()

def delete_user(user):
    del _users[user.username]
    del _user_ids[user.id]

### need a dictionary string to list, append to the list for each word when its added, remvoe from list when its removed
