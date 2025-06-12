#include <cmath>
#include "HuffmanTree.h"
#include "AzrielFunctions.h"
using namespace std;

HuffmanTree::HuffmanTree() {
	letters = map<char, unsigned long long>();
	root = NULL;
}
HuffmanTree::~HuffmanTree() {
	if (root != NULL)
		delete root;
}
void HuffmanTree::buildTree(HuffmanNode_priority_queue & strings) {
	if (strings.size() == 0) return;
	if (strings.size() == 1) {
		root = strings.top();
		strings.pop();
		return;
	}
	while (true) {
		HuffmanNode * x = strings.top();
		strings.pop();
		HuffmanNode * y = strings.top();
		strings.pop();
		root = new HuffmanNode(x, y);
		if (root == NULL) throw "error in memory allocation";
		if (strings.empty()) break;
		strings.push(root);
	}
}
void HuffmanTree::buildTable() {
	letters.clear();
	treeToTable(root);
}
void HuffmanTree::treeToTable(HuffmanNode * HuffmanNode,	unsigned long long code) {
	if (HuffmanNode == NULL) return;
	if (HuffmanNode->getLeft() != NULL) {
		treeToTable(HuffmanNode->getLeft(), code << 1);
		treeToTable(HuffmanNode->getRight(), (code << 1) | 1);
	} else {
		letters[HuffmanNode->getLetter()] = code;
	}
}
unsigned long long HuffmanTree::encodeLetter(char letter) {
	return letters[letter];
}
char HuffmanTree::decodeLetter(unsigned long long code) {
	for(map<char, unsigned long long>::iterator it = letters.begin(); it != letters.end(); ++it)
		if (it->second == code)
			return it->first;
	return NULL;
}
string HuffmanTree::encodeText(string text) {
	map<char, unsigned> chars = map<char, unsigned>();
	HuffmanNode_priority_queue strings = HuffmanNode_priority_queue();
	for (string::const_iterator theText = text.begin(); theText != text.end(); ++theText)
		++(chars[*theText]);
	for (map<char, unsigned>::iterator it = chars.begin(); it != chars.end(); ++it) {
		HuffmanNode * huffmanNode = new HuffmanNode(it->first, it->second);
		if (huffmanNode == NULL) throw "error in memory allocation";
		strings.push(huffmanNode);
	}
	buildTree(strings);
	buildTable();

	string coded = "";
	for(map<char, unsigned>::iterator it = chars.begin(); it != chars.end(); ++it) {
		coded += it->first + UintToString(it->second);
	}
	coded = (char)(coded.length() / 5) + coded;
	if (root->getLeft() == NULL) // the all letters are the same, like this string "777777777777777"
		return coded;
	char temp = 1;
	int counter = 1;
	string codedText = "";



	string::const_iterator theText = text.end();
	do {
		--theText;
		unsigned long long num = letters[*theText];
		int length = (int)(log((double)num) / log((double)2));
		num &= ~(1 << length);
		temp |= num << counter;
		while (length + counter > 7) {
			codedText = temp + codedText;
			temp = (char)(num >>= (8 - counter));
			length -= (8 - counter);
			counter = 0;
		}
		counter += length;
	} while(theText != text.begin());
	return coded + ((counter == 0) ? codedText : temp + codedText);




	// old version
	for (string::const_iterator theText = text.begin(); theText != text.end(); ++theText) {
		unsigned long long num = letters[*theText];
		counter += (int)(log((double)num) / log((double)2) + 1);
		if (counter > 7) {
			counter -= 8;
			temp |= num >> counter;
			coded += temp;
			temp = 0;
		}
		temp |= num << (8 - counter);
	}
	return (counter == 0) ? coded : coded + temp;
}
string HuffmanTree::decodeText(string coded) {
	static const int CharAndFrequency = sizeof(char) + sizeof(unsigned);
	static const int SizeOfChar = sizeof(char);
	string text = "";
	string::const_iterator theText = coded.begin();
	if ((int)coded.length() - 1 <= *theText) return ""; // no data...
	HuffmanNode_priority_queue strings = HuffmanNode_priority_queue();
	for(char counter = *(theText++); counter; --counter) {
		HuffmanNode * huffmanNode = new HuffmanNode(*theText, StringToUint(string(theText + SizeOfChar, theText + CharAndFrequency)));
		if (huffmanNode == NULL) throw "error in memory allocation";
		strings.push(huffmanNode);
		theText += CharAndFrequency;
	}
	buildTree(strings);
	buildTable();
	if (root->getLeft() == NULL) // the all letters are the same, like this string "777777777777777"
		return string(root->getFrequency(), root->getLetter());
	HuffmanNode * HuffmanNode = NULL;
	while (theText != coded.end()) {
		char current = *(theText++);
		for (unsigned char mask = 0x80; mask; mask >>= 1) {
			if (HuffmanNode == NULL) { // this is like a flag
				if (!current) 
					break;
				HuffmanNode = root;
				if (current == 1) {
					break;
				}
				while (!(current & mask))
					mask >>= 1;
				mask >>= 1;
			}
			if (current & mask) {
				HuffmanNode = HuffmanNode->getRight();
				if (HuffmanNode->getRight() == NULL) {
					text += HuffmanNode->getLetter();
					HuffmanNode = root;
				}
			} else {
				HuffmanNode = HuffmanNode->getLeft();
				if (HuffmanNode->getLeft() == NULL) {
					text += HuffmanNode->getLetter();
					HuffmanNode = root;
				}
			}
		}
	}
	return text;
}
