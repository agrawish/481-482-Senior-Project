<?php 
session_start();
$connection = pg_connect(getenv("DATABASE_URL"));
if (!$connection)
{ echo("error connecting..."); }
include('checkAccess.php');
include('studentNavBar.php');
?>
    <div class="container">
        <br/>
        <?php  
        
        

       
        //make form to select the class the user wants to see flashcards for
        if(isset($_SESSION["userid"]))
        {
            //get all the classes a given user is in
            $userID = $_SESSION["userid"];
            $userClasses = '';

            /*
            $userClassesPrepStmt = pg_prepare($connection, "userclassQuery", "SELECT course.course_id, course.course_name
            FROM course
            INNER JOIN classStudents ON course.course_id=classStudents.course_id
            where classStudents.user_id = $1;");
            $userClassesPrepStmt = pg_execute($connection, "userclassQuery", array($userID));
*/

//select all courses instead of just the one the student is enrolled in
            $userClassesPrepStmt = pg_query($connection, "SELECT course.course_id, course.course_name FROM course");
            //$userClassesPrepStmt = pg_execute($connection, "userclassQuery");

            $classID = '';
            $className = '';
            $classesArray = array();
            while($userClass= pg_fetch_row($userClassesPrepStmt))
            {
                $classID = $userClass[0];
                $className = $userClass[1];
                $classesArray[$classID] = $className;    
            }
            echo('<form name="selectClass" id="submitClassForm" method="get" onchange="submitClassChoice()">
            <div class="mb-3 w-50">
            <select class="form-select" aria-label="selectClass" name="selectClass" id="selectClass">
            <option>Select a Class</option>
            <script type="text/javascript">
            function submitClassChoice()
            {
                 document.getElementById("submitClassForm").submit();
             }
            </script>');

            foreach ($classesArray as $key => $value) {
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
        }
        





        if(isset($_GET["selectClass"]))
        {
            $classSelected = $_GET["selectClass"];
            echo("Now select a deck for this class");
        }
       ; //end of selecting class



        //now show all the flashcards for the selected deckname
        echo('<form name="selectDeck" id="submitDeckForm" method="get" onchange="submitDeckChoice()">
        <div class="mb-3 w-50">
        <select class="form-select" aria-label="selectDeck" name="selectDeck" id="selectDeck">
        <option>Select a Deck</option>
        <script type="text/javascript">
        function submitDeckChoice()
        {
             document.getElementById("submitDeckForm").submit();
         }
        </script>
        ');

        $decknamesPrepStmt = pg_prepare($connection, "decknamesQuery", "SELECT DISTINCT flashcard_deckname FROM flashcards WHERE course_id = $1");
        $decknamesPrepStmt = pg_execute($connection, "decknamesQuery", array($classSelected));
        $deckSelected = '';

        while($deck= pg_fetch_row($decknamesPrepStmt)){
            $deckname = $deck[0];
            echo('
            <option 
            
             id="' . "$deckname" . '"
             name="' . "$deckname" . '"
             value="' . "$deckname" . '">
             '. "$deckname" .'
             </option>');
        }

        echo
        ('</select>
        </div>
        </form>');

        echo('
        <div id="difficultyBtnGroup" class="mb-4">
        <p>Optional: Sort By Difficulty...</p>
        <button type="submit" class="btn btn-outline-success" id="sortEasy" onclick="showEasyCards()">Easy</button>    
        <button type="submit" class="btn btn-outline-primary" id="sortMedium" onclick="showMediumCards()">Medium</button> 
        <button type="submit" class="btn btn-outline-danger" id="sortHard" onclick="showHardCards()">Hard</button>  
        <button type="submit" class="btn btn-outline-info" id="reloadCards" onclick="reloadMySelection()">Reload my selection</button>  
        <span> *To sort again, click reload first </span>
        <br/>
        
         </div>     
        ');

        if(isset($_GET["selectDeck"]))
        {
            $deckSelected = $_GET["selectDeck"];
        }

        $cardID = '';
        $cardDef = '';
        $cardTerm = '';
        $cardDifficulty = '';
        $setCardClass = '';

        //create an assoc. array of cardID's and cardDefinitions and save it into a variable
        //to retrieve the right definition, use the id of the button to look up the corresponding value
        //this value will replace the card term 
        $cardDefinitionsArray = array($cardID => $cardDef);
        $cardTermsArray = array($cardID => $cardTerm);

        $cardsForSelectedClassPrepStmt = pg_prepare($connection, "cardsForDeckQuery", "SELECT flashcard_id, flashcard_term, flashcard_definition, flashcard_difficulty FROM flashcards WHERE flashcard_deckname=$1");
        $cardsForSelectedClassPrepStmt = pg_execute($connection, "cardsForDeckQuery", array($deckSelected));

        while($card = pg_fetch_array($cardsForSelectedClassPrepStmt))
        {
            $cardID = $card["flashcard_id"] . "card";
            $cardDef = $card["flashcard_definition"];
            $cardTerm = $card["flashcard_term"];
            $cardDifficulty = $card["flashcard_difficulty"];
            $cardDefinitionsArray[$cardID] = $cardDef;
            $cardTermsArray[$cardID] = $cardTerm;
            

            if($cardDifficulty == "easy")
            {
                $setCardClass = 'card-body bg-success';
            }
            else if($cardDifficulty == 'medium')
            {
                $setCardClass = 'card-body bg-primary';
            }
            else if($cardDifficulty == 'Hard')
            {
                $setCardClass = 'card-body bg-danger';
            }

            echo
            ('
            <div class="myCard ' . $cardDifficulty . '">
             <div class="card text-center w-50 h-50 border border-primary border border-2 rounded text-light">
                
                <div class="' . "$setCardClass" . '">
                    <div class="text-primary">
                    <p class="text-white" id="' . "$cardID" . '">' . $card["flashcard_definition"] .'</p>
    
                    </div>
                </div>

            <div>
            <br/>
                <input type="text" placeholder="Type in your answer here"/>
                </div>
                <br/>
                <div class="card-footer">
                <button type="button" class="btn btn-outline-primary" onclick="showTerm(this.id)" id="' . "$cardID" .
                    '">' . 'Show Term</button>    
                <button type="button" class="btn btn-outline-primary" onclick="showDef(this.id)" id="' . "$cardID" .
                    '">' . 'Show Definition</button>
                    <button type="button" class="btn btn-outline-success" onclick="removeCard(this.id)" id="' . "$cardID" .
                    '">' . 'I got this</button>   
                </div>
                </div>
                </div>
                <br/>
                <script type="text/javascript">
                var definitions = ' . json_encode($cardDefinitionsArray) .';
                var terms = ' . json_encode($cardTermsArray) .';
                function showTerm(id) {
                    document.getElementById(id).innerHTML = terms[id];
                }
                function showDef(id) {
                    document.getElementById(id).innerHTML = definitions[id];
                }
                function removeCard(id) {
                    document.getElementById(id).closest("div.myCard").remove();
                }
                </script>
            ');            
            
            echo('<script>
            function showEasyCards() {
                document.getElementById("sortEasy").click();
                
               var cards = document.getElementsByClassName("myCard");
                for (let index = 0; index < cards.length; index++) {
                    if(cards[index].querySelectorAll(".bg-success").length == 1)
                    {
                        console.log("easy");
                    }
                    else
                    {
                        console.log("not easy");
                        cards[index].remove();
                    }
                }
            }

            function showMediumCards() {
                document.getElementById("sortMedium").click();
                document.getElementById("sortMedium").click();
                var cards = document.getElementsByClassName("myCard");
                 for (let index = 0; index < cards.length; index++) {
                     if(cards[index].querySelectorAll(".bg-primary").length == 1)
                     {
                         console.log("medium");
                     }
                     else
                     {
                         console.log("not medium");
                         cards[index].remove();
                     }
                 }
             }

             function showHardCards() {
                document.getElementById("sortHard").click();
                document.getElementById("sortHard").click();
                document.getElementById("sortHard").click();
                var cards = document.getElementsByClassName("myCard");
                 for (let index = 0; index < cards.length; index++) {
                     if(cards[index].querySelectorAll(".bg-danger").length == 1)
                     {
                         console.log("hard");
                     }
                     else
                     {
                         console.log("not hard");
                         cards[index].remove();
                     }
                 }
             }
             function reloadMySelection(){
                 location.reload();
             }
            </script>');
            }//end of while loop   
        ?>       
    </div>
</body>
</html>
