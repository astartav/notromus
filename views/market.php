 <h2>Рынок - купля-продажа</h2>
        <h5>кстати тут не работает ничего</h5>
        <form>
        <input name='product_list' id='product_list' type='hidden' value='' \>
        <table border=0>
        <tr>
            <td>
            <select size='10' onchange="this.form.product_list.value=this.selectedIndex;" >
                <option value='0'>Хлам железный</option>
                <option value='1'>Небольшой корабль</option>
                <option value='2'>Пища</option>
            </select>
            </td>

            <td>
            <select size='10' onchange="this.form.product_list.value=this.selectedIndex;" >
                <option value='0'>Движок</option>
                <option value='1'>Небольшой корабль</option>
                <option value='2'>Пища</option>
            </select>
            </td>
        </tr>
        <tr>
            <td>
                <input type='button' value='продать' onclick="sendw('sell','product_list');" \><br>
            </td>
            <td>
                <input type='button' value='купить' onclick="sendw('buy','product_list');" \><br>
            </td>
        </tr>
        </form>