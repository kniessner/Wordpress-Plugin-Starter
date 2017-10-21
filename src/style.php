<?php

add_action('admin_head', 'admin_style');

function admin_style() {
  echo '<style>
  .box{
    width: 100%;
    float: left;
  }
	 .card{
      padding-left: 20px;
      padding-top: 0;
      margin-top: 0;
      font-size: 12px;
      background: rgba(250,250,250,0.9);
      width: 200px;
      float: left;
      margin: 10px;
      height: 200px;
      border: 1px solid silver;
	 }
   .person{
         height: 400px;
   }
.person p{
  margin:0;
}
  .tito_ticket h5 {
    font-size: .83em;
    margin: 1.67em 0;
    margin: 0;
    padding: 0;
    font-weight: bolder;
}
.admin_menu{
      background:RGBA(35, 40, 45, 1.00);
      height: 50px;
      display: inline-block;
      width: 100%;
}
.admin_menu a{
  width:150px;
  float:left;
  color:  silver;
  margin: 0px;
  border: 1px solid;
  display: inline;
  padding: 15px;
  }
  </style>';
}


 ?>
