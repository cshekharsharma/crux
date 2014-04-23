/**
 * Program to remove duplcate characters from the given string
 * 
 * @auther Shekhar <shekharsharma705@gmail.com>
 * @since Feb 14, 2014
 */

#include<stdio.h>

char* remove_duplicate_chars(char* str, int len);

int main(int argc, char* argv[]) {

	char* string = "Hello world this is my string";
	int str_len = sizeof(string);///sizeof(string[0]);
	printf("%d ", str_len);return 0;
	printf("Original String: %s\nAfter removing duplicates : %s\n", string, remove_duplicate_chars(string, str_len));
	
	return 0;
}

/**
 * Function takes a string (character array/pointer) as parameter,
 * and returns string with all duplicate characters trimmed
 *
 * @param char* string
 * @return char* output_str
 */
char* remove_duplicate_chars(char* string, int len) {

	int hash[255]={0};
	int i = 0;
	char* output_str;
	int output_char_index = 0;
	
	// Filling hash table with characters and their frequency
	for (i = 0; i < len; i++) {
		if (hash[string[i]] == 0) {
			printf("%d ", len);;
			output_str[output_char_index++] = string[i];
		}
		hash[string[i]]++;
	}
	
	return output_str;
}
