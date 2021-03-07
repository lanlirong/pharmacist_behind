/**
 * 分页函数
 * pno--页数
 * psize--每页显示记录数
 * 分页部分是从真实数据行开始，因而存在加减某个常数，以确定真正的记录数
 * 纯js分页实质是数据行全部加载，通过是否显示属性完成分页功能
 **/
function goPage(pno,psize){
  //var items = document.getElementById("datacount");
  //var num = document.getElementById("datacount").innerText;//表格所有行数(所有记录数)
  var items = document.getElementById("idData").getElementsByTagName("a");
  var num = items.length;//表格所有行数(所有记录数)
  //console.log(num);
  var totalPage = 0;//总页数
  var pageSize = psize;//每页显示行数
  //总共分几页
  if(num/pageSize > parseInt(num/pageSize)){
      totalPage=parseInt(num/pageSize)+1;
    }else{
      totalPage=parseInt(num/pageSize);
    }
  var currentPage = pno;//当前页数
  var startRow = (currentPage - 1) * pageSize+1;//开始显示的行 31
    var endRow = currentPage * pageSize;//结束显示的行  40
    endRow = (endRow > num)? num : endRow;  //40
    console.log(endRow);
    //遍历显示数据实现分页
  for(var i=1;i<(num+1);i++){
    var irow = items[i-1];
    if(i>=startRow && i<=endRow){
      irow.style.display = "block";
    }else{
      irow.style.display = "none";
    }
  }
  var tempStr = "<div style='text-align:center;'><ul class='pager' style='float:right;'>每页10条 共"+num+"条记录  当前第"+currentPage+"页 &nbsp&nbsp&nbsp&nbsp";
  if(currentPage>1){
    tempStr += "<li><a href=\"#\" onClick=\"goPage("+(1)+","+psize+")\">首页</a></li>";
    tempStr += "<li><a href=\"#\" onClick=\"goPage("+(currentPage-1)+","+psize+")\"><上一页</a></li>"
  }else{
    tempStr += "<li><a >首页</a></li>";
    tempStr += "<li><a ><上一页</a></li>";
  }
  if(currentPage<totalPage){
    tempStr += "<li><a href=\"#\" onClick=\"goPage("+(currentPage+1)+","+psize+")\">下一页></a></li>";
    tempStr += "<li><a href=\"#\" onClick=\"goPage("+(totalPage)+","+psize+")\">尾页</a></li></ul></div>";
  }else{
    tempStr += "<li><a >下一页></a></li>";
    tempStr += "<li><a >尾页</a></li></ul></div>";
  }
  document.getElementById("barcon").innerHTML = tempStr;
}