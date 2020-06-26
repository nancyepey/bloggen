// alert('WORKING BABY');


//craeting a function to choose different colors to be displayed (using bootstrap classes)
//by adding and removing different classes




 function colorChange_() {

  // const theATag = document.getElementById('sidebar_categories');
  const theATag = document.querySelectorAll('.cat_color');
  //console.log(theATag);

  diffColor_array = ['badge-primary', 'badge-success', 'badge-danger', 'badge-warning', 'badge-info'];

  // theATag.classList.add('bg-primary');
  //theATag.classList.add(diffColor_array[1]);

  for(i=0; i < theATag.length; i++) {

    for(y=0; y < diffColor_array.length; y++) {

      //console.log(diffColor_array[y]);
      theATag[i].classList.add(diffColor_array[i]);
    }

  }

}

colorChange_();



function colorChanger() {

	// const theATag = document.getElementById('sidebar_categories');
	const theATag = document.querySelectorAll('.sidebar_categories');
	//console.log(theATag);

	diffColor_array = ['bg-primary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info'];

	// theATag.classList.add('bg-primary');
	//theATag.classList.add(diffColor_array[1]);

	for(i=0; i < theATag.length; i++) {

		for(y=0; y < diffColor_array.length; y++) {

			//console.log(diffColor_array[y]);
			theATag[i].classList.add(diffColor_array[i]);
		}

	}

}

colorChanger();