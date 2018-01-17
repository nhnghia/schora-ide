Array.prototype.compare = function(array) { //prototype defines the .compare as method of any object
			if (this.length != array.length) //first compare length - saves us a lot of time
				return false;
			for ( var i = 0; i < this.length; i++) {
				if (this[i] instanceof Array && array[i] instanceof Array) { //Compare arrays
					if (!this[i].compare(array[i])) //!recursion!
						return false;
				} else if (this[i] != array[i]) { //Warning - two diferent objec instances will never be equal: {x:20}!={x:20}
					return false;
				}
			}
			return true;
		}