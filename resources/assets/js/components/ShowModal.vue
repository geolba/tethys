// https://alligator.io/vuejs/vue-modal-component/
<template lang="html">
<div class="modal-backdrop">
  <div class="popup">
    <div class="fm_overlay" >
        <header class="popupbar">
            <slot name="header">
                <b class="popuptitle">Help</b>         
            </slot>             
          <button class="btn-close" @click="close">x</button>
        </header>            
        <section class="pageinfo">             
             <slot name="body">I'm the default body!</slot>
        </section>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: "modal",

  methods: {
    close() {
      this.$emit("close");
    }
  }
};
</script>

<style>
.popup_close {
  background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAB3RJTUUH3AgBFSI5w7714wAAAYlJREFUSMfd1c9KFWEYBvCfJz15NI8RoRnpLohuILyMll1CG0taibhqERkJQWFBixbZpkSwlRs3kdfhBYgaBHU4h8bNGwzTfDNzIgh8YWBmeL7n/TPP8w7/MUb+BbaVeD+NO+g0IG9hEVeaVtPFGjK8wIUa/KPAvsVsHfk0VuNAhh420E7gl/Eth39TlWQsV02GX7kkzzBeGMtDnOawv/GbqdF28A79kkM/8CSSjGAJxyW4DPsxidJoR5uDksM/8TgqP0qQf8bluu/QwetEJ4PcfZF8B1ebKmkSLxNJyp4/YWZYo3VDpt9zRMWrjw+Y+1s3X8dBRYJD3K5zYSpGcRfzNV3ew8SwlbfwoEKKRXU9LfikNpZwkiAvS9LDenRdG8sV5Fsh4V6ik+dVY29jJbdbiuQfcQ1TIeFBopNXIfM/4mLIsox8uyDFbhCV+WQPl1JdLOB9YdY7CRNNxmLr57BfQraVP6sbMesMuzX2H4/dleErbjVV0QLuN3ToREj65rBeGB0CO+bcxhlxQrBXIUNRlQAAAABJRU5ErkJggg==")
    /*icons/close.png*/ no-repeat;
}
/* anything but phone */
@media all and (min-width: 768px) {
 .popup_close {
    width: 20px;
    height: 20px;
    /*margin: 5px;*/
    background-size: 20px 20px;

    float: right;
    cursor: pointer;
    z-index: 800;
  }

 .fm_overlay {
    top: 10em;
    left: 30%;
    max-width: 480px;
    min-width: 300px;
    /*max-height: 90%;*/
    max-height: 500px;
    overflow: auto;
  }
}

/* only phone - bigger buttons */
@media all and (max-width: 767px) {
  .popup_close {
    background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAB3RJTUUH3AcXFyot5RvgagAAAbdJREFUaN7t2DFOAkEUxvE/6AFMUBMoIDExHsMDqJVgZ+KJKIyFBSjGxCN4DbUSC6XRys5GZDXYMAkh7rLz5s0smPmSLXnDj2Hhm4WYmJiYlFSAG2DTw+wS0AZ2fSOqQB8YA09ATXF2GbiczB4CeyEQY2VMGbiYmT0E9kMgtDB/IbxgahkIV0wWQhWTByHF5EGY68sFY4OwxdggpjEHEsit5ULmegDW5/zEnglnfwAbtpA68CxcsJ+yM2WgK5w5Ag6lXy9NjCui6XrD14EXB0x1ERAm28Cr8I3cA+fC134Dx9p/ii47I7kSzZ0oCuMVEQoTBGHS8IQJivCFKQShjUmAVtEnxgYwWHaE6U7XDpCBpD9pR9JibbpZMERX8WYvBKONKATjCzFbNJcaEQQTCuEVUwJOHar4u8MRoLIIO2Fqh8bhzBnRUepOhWE0ERqYRymmLVzwBzjJmLsDvAln3wFrtpDW5JP1UQClrfkKWJHsig3GtsXaYnpShEkzB0ZaxfMeAXqTe9Y5WZgEOPJ4nlFDZGFcEfMw6ohpzEgZkYbxhpjGfCojZjHeESZbnp8BrBITExPzr/MLneElMzKD908AAAAASUVORK5CYII=")
      /*mobile_icons/close.png*/ no-repeat;
    width: 25px;
    height: 25px;
    background-size: 25px 25px;

    float: right;
    cursor: pointer;
    z-index: 800;
  }

  .fm_overlay {
    bottom: 4em;
    left: 20px;
    right: 20px;

    max-height: 70%;
    overflow-y: auto;
  }

  /*.touch .fm_basemap_list{  max-height: 80%; overflow-y: auto;}*/
}

.popup .fm_overlay {
  position: fixed;
  width: 300px;

  left: 50%;
  margin-left: -150px;
  top: 50%;
  margin-top: -150px;

  z-index: 9999;
  padding: 10px;

  border-radius: 1px;
  box-shadow: 0 0 0 10px rgba(34, 34, 34, 0.6);
}

.popupbar {
  background: dimgray;
  /* color: white; */
  padding-left: 4px;
  height: 25px;

  border-bottom: 1px solid #eeeeee;
  color: #4aae9b;
  justify-content: space-between;
}

.popup_close {
  color: rgb(220, 220, 220);
  /*background: gray;*/
  border: 1px solid darkgray;
  border-radius: 4px;
  font-size: 16px;
  font-weight: bold;
  width: 16px;
  height: 16px;
  text-align: center;
  float: right;
  cursor: pointer;
  background-size: 16px 16px;
}

.fm_overlay {
  background-color: white;
  padding: 2px 5px 2px;
  max-height: 500px;
  overflow: auto;
  font-size: 14px;
}

.pageinfo {
  font-size: small;
}

.pageinfo h1 {
  background-color: gray;
  color: white;
  font-size: small;
  font-weight: normal;
  margin-top: 5px;
  margin-bottom: 3px;
  padding: 1px;
  padding-left: 6px;
}

.license {
  font-size: xx-small;
}

.star {
  padding: 5px;
  text-align: left;
}


.modal-backdrop {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background-color: rgba(0, 0, 0, 0.3);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal {
  background: #ffffff;
  box-shadow: 2px 2px 20px 1px;
  overflow-x: auto;
  display: flex;
  flex-direction: column;
}

.modal-header,
.modal-footer {
  padding: 15px;
  display: flex;
}

.modal-header {
  border-bottom: 1px solid #eeeeee;
  color: #4aae9b;
  justify-content: space-between;
}

.modal-footer {
  border-top: 1px solid #eeeeee;
  justify-content: flex-end;
}

.modal-body {
  position: relative;
  padding: 20px 10px;
  max-width: 150px;
  max-height: 150px;
}

.btn-close {
  border: none;
  font-size: 20px;
  font-weight: bold;
  color: #4aae9b;
  background: transparent;
    float: right;
    cursor: pointer;
}

.btn-green {
  color: white;
  background: #4aae9b;
  border: 1px solid #4aae9b;
  border-radius: 2px;
}
</style>
