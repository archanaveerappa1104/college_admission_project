  </div></div>
<script>
function updateClock(){
  const n=new Date();
  const el=document.getElementById('live-clock');
  if(el)el.textContent=n.toLocaleDateString('en-IN',{weekday:'short',day:'2-digit',month:'short',year:'numeric'})+' '+n.toLocaleTimeString('en-IN',{hour:'2-digit',minute:'2-digit',second:'2-digit'});
}
setInterval(updateClock,1000);updateClock();
function updateDepts(){
  const lv=document.getElementById('course_level');
  const dg=document.getElementById('dept_group');
  if(!lv||!dg)return;
  const d=<?=json_encode(getDepts())?>;
  const sel=lv.value;
  if(!sel){dg.innerHTML='<option value="">-- Select level first --</option>';return;}
  let h='<option value="">-- Select department --</option>';
  for(const[cat,list] of Object.entries(d[sel]||{})){
    h+=`<optgroup label="${cat}">`;
    for(const x of list)h+=`<option value="${x}">${x}</option>`;
    h+='</optgroup>';
  }
  dg.innerHTML=h;
}
</script></body></html>
