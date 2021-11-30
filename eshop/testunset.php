<!DOCTYPE html>
<html>

<body>

    <?php
    if ($_POST) {

        // for ($x = 0; $x < count($_POST['product']); [$x++]) {
        //     if ($_POST['product'][$x] === '' && $_POST['quantity'][$x] === '') {
        //         unset($_POST['product'][$x]);
        //         unset($_POST['quantity'][$x]);
        //     }
        // }
        //key is their array id , value is what the array is 


        var_dump($_POST['product']);
        var_dump($_POST['quantity']);
    }
    ?>


    <form method="POST">

        <?php
        $product = array("bmw", "toyota", "audi");

        foreach ($product as $key => $value) {
            if ($value === '') {
                unset($pRow);
            }
        }



        ?>
        <div class='pRow'>
            <select name="product[]">
                <option value=""></option>
                <option value="BMW">bmw</option>
                <option value="Toyota">toyota</option>
                <option value="Audi">audi</option>
            </select>
            <select name="quantity[]">
                <option value=""></option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
        </div>



        <input type='submit' value='Save' class='btn btn-primary' />
        <div class="d-flex justify-content-center">
            <button type="button" class="add_one btn mb-3 mx-2">Add More Product</button>
            <button type="button" class="delete_one btn mb-3 mx-2">Delete Last Product</button>
        </div>
    </form>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>


    <script>
        document.addEventListener('click', function(event) {
            if (event.target.matches('.add_one')) {
                var element = document.querySelector('.pRow');
                var clone = element.cloneNode(true);
                element.after(clone);
            }
            if (event.target.matches('.delete_one')) {
                var total = document.querySelectorAll('.pRow').length;
                if (total > 1) {
                    var element = document.querySelector('.pRow');
                    element.remove(element);
                }
            }
        }, false);
    </script>





</body>

</html>