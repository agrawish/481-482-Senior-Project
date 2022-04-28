<?php 
session_start();
$connection = pg_connect(getenv("DATABASE_URL"));
if (!$connection)
{ echo("error connecting..."); }
include('checkAccess.php');
include('navBar.php');
?>
</br>
<?php 
     $teacherClass = '';
    if(isset($_SESSION["userid"]))
    {
        //get all the classes a given user is in
        $userID = $_SESSION["userid"];
        $teacherClassesPrepStmt = pg_prepare($connection, "teacherClassesQuery", "SELECT course_id, course_name FROM course where course_teacher = $1");
        $teacherClassesPrepStmt = pg_execute($connection, "teacherClassesQuery", array($userID));
        $teacherClassID = '';
        $teacherClassName = '';
        $teacherClassesArray = array();
        while($teacherClass= pg_fetch_row($teacherClassesPrepStmt))
        {
            $teacherClassID = $teacherClass[0]; //course id
            $teacherClassName = $teacherClass[1]; //course name
            $teacherClassesArray[$teacherClassID] = $teacherClassName;    
        }
        //var_dump($teacherClassesArray);

        echo('<form name="selectTeacherClass" class= "w-50 mx-auto" id="submitTeacherClassForm" method="get" onchange="submitTeacherClassChoice()">
        
        <div class="mb-3">
        <select class="form-select" aria-label="selectClass" name="selectTeacherClass" id="selectTeacherClass">
        <option>Select the class you want to make cards for</option>
        <script type="text/javascript">
        function submitTeacherClassChoice()
        {
             document.getElementById("submitTeacherClassForm").submit();
         }
        </script>');

        foreach ($teacherClassesArray as $key => $value) {
        //key is the course id, value is the course name, which is in the dropdown       
            echo('<option
            id="' . "$key" . '"
            name="' . "$value" . '"
            value="' . "$key" . '"
            >
            ' . "$value" .'
            </option>
            ');
            }

        echo
        ('</select>  
            </div>
        </form>
        ');
    
        if(isset($_GET["selectTeacherClass"]))
        {
           // assign a number to the card as the id so that they can be posted
           echo('<form name="deckCards" id="submitDeckCards" class="mx-auto " method="POST">
           <input type="text" id="deckName" class="form-control w-50 mx-auto" name="deckName" required placeholder= "Name of Deck"/>
           <br/>
           ');
           $teacherClass = $_GET["selectTeacherClass"];
            
           echo('
           <div id="FlashcardSet">
           <div id="wholeCard" class="was-validated mx-auto">
           <div class="card border-success mb-2 bg-light ">
         <div class="card-header text-success mx-auto">
           Card
         </div>
         <div class="row">
           <div class="col m-2">
             <div class="form-floating mb-2">
               <input type="text" required class="form-control" id="cardTerm" name= "cardTerm[]" placeholder="Flashcard Term">
               <label for="cardTerm">Term</label>
             </div>
           </div>
           <div class="col m-2">
             <div class="form-floating mb-2">
               <input type= "text" required class="form-control" name= "cardDef[]" placeholder="Flashcard Definition" id="cardDef">
               <label for="cardDef">Definition</label>  
             </div>
           </div>
           <div class="col m-2">
             <select class="form-select" aria-label="selectDifficultyLevel" required name="cardDifficulty[]">
               <option selected value="easy">Easy (Default)</option>
               <option value="medium">Medium</option>
               <option value="Hard">Hard</option>
             </select>
           </div>
         </div>
       </div>
           </div>
           </div>
           
        
           ');
   
        echo('
        <div class=mx-auto">
        <button type="submit" class="btn btn-primary" onclick="" name="cardsSubmit">Submit my Cards</button>
        <button type="button" class="btn btn-success" onclick="createCard()">Add another card</button>
        <button type="button" class="btn btn-danger" onclick="removeCard()">Remove the last card
        </button>
        </div>
        <script type="text/javascript">
        function createCard()
        {
            const card = document.getElementById("wholeCard");
            const newCard = card.cloneNode(true)
            document.getElementById("FlashcardSet").appendChild(newCard);   
         }
         function removeCard()
        {
            const set = document.getElementById("FlashcardSet");
            const lastCard = set.lastChild;
            set.removeChild(lastCard);  
         }
        </script>
        </form>
        ');

        if (isset($_POST["cardsSubmit"]) && isset($_POST["deckName"])) {
            
            $deckname = $_POST["deckName"];
            $num = count(($_POST["cardDef"]));
            pg_prepare($connection, "insertCardQuery", "INSERT into flashcards (flashcard_term, flashcard_definition, course_id, flashcard_deckname, flashcard_difficulty) VALUES ($1, $2, $3, $4, $5)");
                
            for ($i=0; $i < $num; $i++) { 
                $term = $_POST["cardTerm"][$i];
                $def = $_POST["cardDef"][$i];
                $difficulty = $_POST["cardDifficulty"][$i];
                pg_execute($connection, "insertCardQuery", array($term, $def, $teacherClass, $deckname, $difficulty));
                

            }
        }
    
    }
           
    
    
    
    
    }


pg_close($connection);
?>